<?php

namespace App\Http\Controllers\Employee;

class ExportUsersController
{
        public function __invoke()
        {
            return \Excel::download(new \App\Exports\Users, 'users.xlsx');
        }
}
