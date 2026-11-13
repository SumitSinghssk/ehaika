<?php

namespace App\Http\Controllers\Website;

use App\Enums\EnquiryStatus;
use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public const SUBJECTS = [
        'demo' => 'Book a demo',
        'evaluation' => 'Product / model evaluation',
        'risk' => 'AI risk assessment / audit',
        'compliance' => 'Compliance / regulatory support',
        'partnership' => 'Partnership or collaboration',
        'general' => 'General enquiry',
    ];

    public function show(Request $request)
    {
        $subject = array_key_exists($request->query('subject'), self::SUBJECTS) ? $request->query('subject') : 'demo';

        return view('website.contact', ['subjects' => self::SUBJECTS, 'subject' => $subject]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'company' => ['nullable', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+().\s-]{6,40}$/'],
            'subject' => ['required', Rule::in(array_keys(self::SUBJECTS))],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ], [
            'phone.regex' => 'Enter a phone number using digits, spaces and + ( ) - only.',
            'message.min' => 'Tell us a little more about your AI use case (at least 10 characters).',
        ]);

        $enquiry = Enquiry::create([
            'source' => 'website-contact',
            'source_url' => url()->previous() ?: route('contact'),
            'status' => EnquiryStatus::NEW->value,
            'data' => [...$data, 'subject' => self::SUBJECTS[$data['subject']]],
        ]);

        rescue(fn () => notify(
            'enquiry',
            'New enquiry: '.self::SUBJECTS[$data['subject']],
            "{$data['name']} ({$data['email']}) sent a message from the website.",
            ['enquiry_id' => $enquiry->id],
            route('admin.enquiries.show', $enquiry),
        ), report: false);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'name' => $data['name'],
                'message' => 'Thank you, '.$data['name'].'. A member of the team will get back to you with practical next steps.',
            ]);
        }

        $back = $request->input('_from') === 'home' ? redirect()->to(url('/').'#contact') : to_route('contact');

        return $back->with('sent', $data['name']);
    }
}
