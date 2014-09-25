package ru.crystals.vaverjanov.dreamkas.view;

import com.octo.android.robospice.SpiceManager;

import ru.crystals.vaverjanov.dreamkas.model.DreamkasFragments;
import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;

public interface IChangeFragmentHandler
{
    void onFragmentChange(DreamkasFragments target);
    SpiceManager getRestClient();
}
