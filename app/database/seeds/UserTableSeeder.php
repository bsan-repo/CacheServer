<?php
 
class UserTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('users')->delete();
 
        User::create(array(
            'username' => 'client_app_user',
            'password' => Hash::make('xPK81Kan1ejtN71Z4mKw')
        ));
 
        User::create(array(
            'username' => 'admin_hq_22',
            'password' => Hash::make('bO3nlCLFr2ZMhp34aqmj')
        ));
    }
 
}
?>