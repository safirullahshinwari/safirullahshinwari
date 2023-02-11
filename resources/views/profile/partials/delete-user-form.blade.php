<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-danger-900">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>
    <button onclick.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="btn btn-primary mt-4 pr-4 pl-4">
        Delete Account</button>
    <modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable id="confirmation_delete">
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>
            <div class="mt-6">
                <input type="password" for="password" value="Password" class="sr-only" />
                <input id="password" name="password" type="password" class="mt-1 block w-3/4" placeholder="Password" />
                <input messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="$dispatch('close')" btn btn-secondary mt-4 pr-4 pl-4>
                    Cancel
                </button>
            </div>
            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">
                Delete Acount
            </button>
        </form>
    </modal>
</section>