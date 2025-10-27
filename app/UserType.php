<?php

namespace App;

enum UserType: string
{
    case Admin = 'admin';
    case SuperAdmin = 'superAdmin';
    case Supplier = 'supplier';
    case User = 'user';

    //case Editor = 'editor';
    //case Subscriber = 'subscriber';
    //case Guest = 'guest';
}
