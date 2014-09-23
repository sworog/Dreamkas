package ru.crystals.vaverjanov.dreamkas.controller;

import android.app.Application;
import com.octo.android.robospice.SpiceService;
import com.octo.android.robospice.persistence.CacheManager;
import com.octo.android.robospice.persistence.exception.CacheCreationException;
import com.octo.android.robospice.persistence.springandroid.json.jackson2.Jackson2ObjectPersister;

import ru.crystals.vaverjanov.dreamkas.model.AuthObject;
import ru.crystals.vaverjanov.dreamkas.model.Token;

public class LighthouseSpiceService extends SpiceService {

    @Override
    public CacheManager createCacheManager(Application application) throws CacheCreationException
    {
        CacheManager manager = new CacheManager();
        Jackson2ObjectPersister<AuthObject> persister1 = new Jackson2ObjectPersister<AuthObject>(getApplication(), AuthObject.class);
        Jackson2ObjectPersister<Token> persister2 = new Jackson2ObjectPersister<Token>(getApplication(), Token.class);
        manager.addPersister(persister1);
        manager.addPersister(persister2);
        return manager;
    }
}
