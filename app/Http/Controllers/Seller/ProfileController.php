<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse; // Tambahkan ini
use Illuminate\Support\Facades\Redirect; // Tambahkan ini
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Kantin;

class ProfileController extends Controller
{
    /**
     * Display the seller's profile form.
     *
     * Ini akan menggunakan view seller.profile.profile
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->load('kantin'); // Eager load kantin milik user

        // Prepare payment data
        $paymentMethods = [];
        $paymentSettings = (object) [
            'instructions' => '',
            'auto_confirm' => false
        ];

        if ($user->kantin) {
            $paymentMethods = json_decode($user->kantin->payment_methods, true) ?? [];
            $paymentSettings->instructions = $user->kantin->payment_instructions ?? '';
            $paymentSettings->auto_confirm = $user->kantin->auto_confirm_payment ?? false;
        }

        return view('seller.profile.profile', [
            'user' => $user,
            'kantin' => $user->kantin, // Kirim data kantin ke view
            'paymentMethods' => $paymentMethods,
            'paymentSettings' => $paymentSettings,
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }

    /**
     * Update the seller's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
        ]);

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->route('seller.profile.edit')
            ->with('status', 'profile-updated')
            ->with('message', 'Informasi profil berhasil diperbarui.');
    }

    /**
     * Update the seller's kantin information.
     */
    public function updateKantin(Request $request)
    {
        try {
            $validated = $request->validate([
                'kantin_name' => 'required|string|max:255',
                'kantin_description' => 'nullable|string',
                'kantin_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'kantin_is_open' => 'boolean'
            ]);

            $user = auth()->user();
            $kantin = Kantin::where('user_id', $user->id)->first();

            if (!$kantin) {
                $kantin = new Kantin();
                $kantin->user_id = $user->id;
            }

            // Handle image upload
            if ($request->hasFile('kantin_image')) {
                try {
                    // Delete old image if exists
                    if ($kantin->image) {
                        Storage::disk('public')->delete($kantin->image);
                    }

                    // Store new image
                    $path = $request->file('kantin_image')->store('kantin-images', 'public');
                    $kantin->image = $path;
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengupload gambar: ' . $e->getMessage()
                    ], 422);
                }
            }

            // Update kantin details
            $kantin->name = $validated['kantin_name'];
            $kantin->description = $validated['kantin_description'];
            $kantin->is_open = $request->boolean('kantin_is_open', false);

            $kantin->save();

            // Clear cache
            cache()->forget('kantins');
            cache()->forget('kantin_' . $kantin->id);

            // Refresh model
            $kantin->refresh();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Detail kantin berhasil diperbarui',
                    'kantin' => $kantin
                ]);
            }

            return redirect()->route('seller.profile.edit')
                ->with('status', 'kantin-updated')
                ->with('message', 'Detail kantin berhasil diperbarui');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('seller.profile.edit')
                ->with('status', 'error')
                ->with('message', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the seller's payment settings.
     */
    public function updatePayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_methods' => 'required|array|min:1',
                'payment_methods.*' => 'in:gopay,ovo,dana,cod',
                'payment_instructions' => 'nullable|string|max:500',
                'auto_confirm_payment' => 'boolean'
            ]);

            $user = auth()->user();
            $kantin = Kantin::where('user_id', $user->id)->first();

            if (!$kantin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kantin tidak ditemukan'
                ], 404);
            }

            // Update payment settings
            $kantin->payment_methods = json_encode($validated['payment_methods']);
            $kantin->payment_instructions = $validated['payment_instructions'] ?? null;
            $kantin->auto_confirm_payment = $request->boolean('auto_confirm_payment', false);

            $kantin->save();

            // Clear cache
            cache()->forget('kantin_' . $kantin->id);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengaturan pembayaran berhasil diperbarui'
                ]);
            }

            return redirect()->route('seller.profile.edit')
                ->with('status', 'payment-updated')
                ->with('message', 'Pengaturan pembayaran berhasil diperbarui');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('seller.profile.edit')
                ->with('status', 'error')
                ->with('message', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete the seller's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/'); // Redirect ke halaman utama setelah hapus akun
    }
}
