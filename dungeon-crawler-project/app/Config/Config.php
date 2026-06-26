<?php

namespace App\Config;

class Config
{

    const bool AutoCacheEveryChange = false;

    // if the game should be loaded from cache into memory
    const bool RestoreGameFromCacheOnInit = false;

    // which caching mode should be used to load game from cache into memory
    const string RestoreGameFromCacheMode = "file";

    /// INTERNAL SETTINGS \\\
    const string UpdateDifferenceDataPath = '../../data/update_difference/';

    const string ConfigKey = 'config';

}