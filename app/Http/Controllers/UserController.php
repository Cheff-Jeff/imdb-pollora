<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8|same:password',
        ]);

        try {
            $userId = wp_insert_user([
                'user_login' => $validated['name'],
                'user_email' => $validated['email'],
                'user_pass' => $validated['password'],
                'display_name' => $validated['name'],
                'role' => 'subscriber',
            ]);

            if (is_wp_error($userId)) {
                return back()
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->withErrors(['general' => $userId->get_error_message()]);
            }

            wp_set_current_user($userId);
            wp_set_auth_cookie($userId);
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->except('password', 'password_confirmation'))->withErrors(['general' => 'Failed to create user.']);
        }

        return redirect()->route('account');
    }

    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|',
        ]);

        try {
            $user = wp_authenticate($validated['email'], $validated['password']);
            if (is_wp_error($user)) {
                return back()->withInput($request->except('password'))->withErrors(['general', $user->get_error_message()]);
            }

            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);

            return redirect()->route('account');
        } catch (\Throwable $th) {
            return back()->withInput($request->except('password'))->withErrors(['general', $th->getMessage()]);
        }
    }

    public function handleSaveMovie(Request $request)
    {
        if (!is_user_logged_in()) {
            return back();
        }

        if (!$request->isMethod('post')) {
            return back();
        }

        if (!$request->has('movie')) {
            return back();
        }

        $user = wp_get_current_user();
        $userMeta = get_user_meta($user->ID, 'movies', true) ?: [];
        $movieId = intval($request->input(['movie']));

        if (count($userMeta) === 0) {
            update_user_meta($user->ID, 'movies', [$movieId]);
            return back();
        }

        if (in_array($request->input(['movie']), $userMeta)) {
            $filterMeta = array_values(array_filter(array_map('intval', $userMeta), fn($id) => $id !== $movieId));

            update_user_meta($user->ID, 'movies', $filterMeta);
        } else {
            $userMeta[] = $movieId;
            update_user_meta($user->ID, 'movies', $userMeta);
        }

        return back();
    }

    public function signUp()
    {
        return view('sign-up');
    }

    public function signIn()
    {
        return view('sign-in');
    }

    public function account()
    {
        if (!is_user_logged_in()) {
            return redirect()->route('account.sign-in');
        }

        $user = wp_get_current_user();
        $userMeta = get_user_meta($user->ID, 'movies', true) ?: [];
        return view('account', ['movies' => $userMeta]);
    }
}
