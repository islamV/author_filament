<?php

return[
    'forbidden' => 'You are not allowed to create an article',
    'article' => [
        'added' => 'Article added',
        'updated' => 'Article updated',
        'deleted' => 'Article deleted',
        'list' => 'List of articles',
        'by_category' => 'List of articles by category',
        'details' => 'Article details',
        'search' => 'Search for article',
        'published' => 'Article published',
        'not_published' => 'Article not published yet',
        'rated' => 'Article rated',
    ],
    'book_parts' => [
        'added' => 'Book part added',
        'updated' => 'Book part updated',
        'deleted' => 'Book part deleted',
        'list' => 'List of book parts',
        'details' => 'Book part details',
        'search' => 'Search for book part',
        'array' => 'The book parts must be an array.',
        'chapter' => [
            'required' => 'The chapter field is required.',
            'string' => 'The chapter must be a string.',
            'max' => 'The chapter may not be greater than 255 characters.',
        ],
    ],
    'category' => [
        'added' => 'Category added',
        'updated' => 'Category updated',
        'deleted' => 'Category deleted',
        'list' => 'List of categories',
        'details' => 'Category details',
        'search' => 'Search for category',
    ],
    'user' => [
        'bad_credentials' => 'Bad Credentials',
        'loggedIn' => 'User logged in',
        'registered' => 'User registered',
        'logout' => 'User logged out',
        'required_id' => "User ID is required",
        'You cannot follow yourself' => 'You cannot follow yourself',
        'Already following' => 'Already following',
        'followed' => 'Followed',
        'unfollowed' => 'Unfollowed',
        'follow' => 'Follow',
        'unfollow' => 'Unfollow',
    ],
    "chat_send" => "Message Sent",
    'title' => [
        'required' => 'The title field is required.',
        'string' => 'The title must be a string.',
        'max' => 'The title may not be greater than 255 characters.',
    ],
    'description' => [
        'required' => 'The description field is required.',
        'string' => 'The description must be a string.',
    ],
    'image' => [
        'required' => 'The image field is required.',
        'image' => 'The uploaded file must be an image.',
        'mimes' => 'The image must be of type: jpeg, png, jpg, gif.',
        'max' => 'The image size must not exceed 2MB.',
    ],
    'category_id' => [
        'required' => 'The category field is required.',
        'exists' => 'The selected category does not exist.',
    ],
    'book_page' => [
        'array' => 'The book pages must be an array.',
        'page_number' => [
            'required' => 'The page number field is required.',
            'integer' => 'The page number must be an integer.',
            'min' => 'The page number must be at least 1.',
        ],
        'content' => [
            'required' => 'The content field is required.',
            'string' => 'The content must be a string.',
        ],
    ],
    'book_unFavourite' => 'Book removed from favourites',
    'book_favourite' => 'Book added to favourites',


    'requested_amount.required' => 'The requested amount is required.',
    'requested_amount.numeric' => 'The requested amount must be a number.',
    'requested_amount.min' => 'The requested amount must be at least 0.',

    'payment_method.required' => 'The payment method is required.',
    'payment_method.string' => 'The payment method must be a valid string.',
    'payment_method.in' => 'The selected payment method is invalid.',

    'phone.required_if' => 'The phone number is required for Vodafone, Orange, and Etisalat.',
    'phone.string' => 'The phone number must be a valid string.',

    'beneficiary_name.required_if' => 'The beneficiary name is required for banking.',
    'beneficiary_name.string' => 'The beneficiary name must be a valid string.',
    'beneficiary_name.max' => 'The beneficiary name may not be greater than 255 characters.',

    'bank_name.required_if' => 'The bank name is required for banking.',
    'bank_name.string' => 'The bank name must be a valid string.',
    'bank_name.max' => 'The bank name may not be greater than 255 characters.',

    'iban.required_if' => 'The IBAN is required for banking.',
    'iban.string' => 'The IBAN must be a valid string.',
    'iban.regex' => 'The IBAN format is invalid.',

    'swift_bio_code.required_if' => 'The SWIFT/BIC code is required for banking.',
    'swift_bio_code.string' => 'The SWIFT/BIC code must be a valid string.',
    'swift_bio_code.size' => 'The SWIFT/BIC code must be exactly 8 characters.',

    'beneficiary_address.string' => 'The beneficiary address must be a valid string.',
    'beneficiary_address.max' => 'The beneficiary address may not be greater than 255 characters.',

    'email_binance.required_if' => 'The Binance email is required for Binance and Payoneer.',
    'email_binance.email' => 'The Binance email must be a valid email address.',
    'email_binance.max' => 'The Binance email may not be greater than 255 characters.',

    'wallet_id.required_if' => 'The wallet ID is required for InstaPay, Payeer, and Perfect Money.',
    'wallet_id.string' => 'The wallet ID must be a valid string.',
    'wallet_id.max' => 'The wallet ID may not be greater than 255 characters.',

    'wallet_name.required_if' => 'The wallet name is required for InstaPay.',
    'wallet_name.string' => 'The wallet name must be a valid string.',
    'wallet_name.max' => 'The wallet name may not be greater than 255 characters.',

    'user_not_found' => 'User not found.',

    'roles' => [
        'required' => 'Invalid role for this action.',
        'reader_only' => 'Only reader role is allowed for this step.',
    ],

    'invitation_code' => [
        'invalid' => 'Invitation code is not valid.',
        'used' => 'Invitation code has already been used.',
        'cannot_use_own' => 'You cannot use your own invitation code.',
        'already_used_by_user' => 'You have already used this invitation code.',
    ],

];
