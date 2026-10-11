<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageAdminController extends Controller
{
    public function index()
    {
        return view('pages.admin.messages', [
            'messages' => ContactMessage::latest()->get()->map->toAdminArray()->values(),
        ]);
    }

    public function updateStatus(Request $request, ContactMessage $message)
    {
        $request->validate([
            'status' => ['required', 'in:'.implode(',', ContactMessage::STATUTS)],
        ]);

        $message->update(['status' => $request->string('status')->toString()]);

        return back()->with('success', 'Statut du message mis à jour.');
    }

    /**
     * Suppression définitive (route réservée à l'admin connecté).
     */
    public function destroy(ContactMessage $message)
    {
        $numero = $message->message_number;
        $message->delete();

        return back()->with('success', "Message {$numero} supprimé définitivement.");
    }
}
