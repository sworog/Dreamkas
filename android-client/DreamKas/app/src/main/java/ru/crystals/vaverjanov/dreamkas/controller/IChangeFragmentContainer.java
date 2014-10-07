package ru.crystals.vaverjanov.dreamkas.controller;

import com.octo.android.robospice.SpiceManager;

import ru.crystals.vaverjanov.dreamkas.model.DrawerMenu;

public interface IChangeFragmentContainer
{
    void onFragmentChange(DrawerMenu.AppStates target);
    SpiceManager getRestClient();
}
