<?php
/**
 * ChangeRootPassword.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2023-02-13 20:56:16
 * @modified   2023-02-13 20:56:16
 */

namespace Beike\Console\Commands;

use Beike\Models\AdminUser;
use Illuminate\Console\Command;

class ChangeRootPassword extends Command
{
    protected $signature = 'root:password';

    protected $description = '修改后台Root账号(第一个管理员)';

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        // Step 1: Choose whether to select or create an admin
        $choice = $this->choice(
            'Please choose an action:',
            ['Select an existing admin', 'Create a new admin'],
            0
        );

        if ($choice === 'Create a new admin') {

            // --- Ask for Name ---
            $name = $this->ask('Enter the name of the new admin:');
            if (! $name) {
                $this->error('Name cannot be empty.');
                return;
            }

            // --- Ask for Email ---
            $email = $this->ask('Enter the email of the new admin:');
            if (! $email) {
                $this->error('Email cannot be empty.');
                return;
            }

            $user = new AdminUser();
            $user->name  = $name;
            $user->email = $email;

        } else {
            // Step 1B: Select existing admin
            $users = AdminUser::query()->pluck('id', 'email')->toArray();

            if (empty($users)) {
                $this->error('No admin users found. Please create a new one.');
                return;
            }

            $userEmail = $this->choice('Select an admin:', array_keys($users));
            $userId    = $users[$userEmail];
            $user      = AdminUser::query()->find($userId);

            // Allow updating their name too
            $name = $this->ask("Enter name for this admin ({$user->name}) or press Enter to keep current:");
            if ($name) {
                $user->name = $name;
            }
        }

        // Step 2: Ask for new password
        $newPassword = $this->secret("Enter a new password for {$user->email}:");

        if (! $newPassword) {
            $this->error('Password cannot be empty.');
            return;
        }

        // Step 3: Ask for global admin permission
        $isGlobal = $this->choice(
            'Set this admin as a global admin?',
            ['yes', 'no'],
            1 // default: no
        );

        // Step 4: Save admin info
        $user->password         = bcrypt($newPassword);
        $user->is_global_admin  = $isGlobal === 'yes' ? 1 : 0;
        $user->active = 1;
        
        $user->saveOrFail();

        $this->info("Admin '{$user->email}' has been successfully saved!");
    }

}
