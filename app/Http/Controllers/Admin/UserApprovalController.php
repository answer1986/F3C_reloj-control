<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\UserApproved;
use App\Notifications\UserRejected;

class UserApprovalController extends Controller
{
    /**
     * Display a listing of pending users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pendingUsers = User::where('is_active', false)->get();
        return view('admin.users.pending', compact('pendingUsers'));
    }

    /**
     * Approve a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->save();
    
        // Enviar notificación de aprobación
        $user->notify(new UserApproved());
    
        return redirect()->back()->with('success', 'Usuario aprobado exitosamente.');
    }
    
    /**
     * Reject a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->notify(new UserRejected());
        $user->delete();
    
        return redirect()->back()->with('success', 'Usuario rechazado y eliminado exitosamente.');
    }
}
