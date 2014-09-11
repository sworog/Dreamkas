package ru.crystals.vaverjanov.dreamkas.controller;

import com.google.android.apps.common.testing.ui.espresso.IdlingResource;

public class BaseRequest implements IdlingResource {
    @Override
    public String getName() {
        return null;
    }

    @Override
    public boolean isIdleNow() {
        return false;
    }

    @Override
    public void registerIdleTransitionCallback(ResourceCallback resourceCallback) {

    }
}
