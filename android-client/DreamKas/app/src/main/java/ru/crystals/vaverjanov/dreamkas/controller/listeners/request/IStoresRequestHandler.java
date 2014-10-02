package ru.crystals.vaverjanov.dreamkas.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;

import ru.crystals.vaverjanov.dreamkas.model.api.NamedObjects;

public interface IStoresRequestHandler
{
    void onGetStoresSuccessRequest(NamedObjects objects);
    void onGetStoresFailureRequest(SpiceException spiceException);
}
