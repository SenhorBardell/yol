<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new CompileRefs);
Artisan::add(new Notifications);
Artisan::add(new Scheduler);
Artisan::add(new EmergencyCalls);
Artisan::add(new linkVTypes);
Artisan::add(new ResizeAllImages);
//Artisan::add(new Retrieve);
Artisan::add(new CategoriesDescription);