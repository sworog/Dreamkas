package ru.crystals.vaverjanov.dreamkas.controller.listeners;

import com.octo.android.robospice.persistence.exception.SpiceException;

import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.model.Token;

public interface IStoresRequestHandler
{
    void onGetStoresSuccessRequest(NamedObjects objects);
    void onGetStoresFailureRequest(SpiceException spiceException);
}
