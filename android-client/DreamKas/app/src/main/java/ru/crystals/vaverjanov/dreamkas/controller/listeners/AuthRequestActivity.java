package ru.crystals.vaverjanov.dreamkas.controller.listeners;

import com.octo.android.robospice.persistence.exception.SpiceException;

import ru.crystals.vaverjanov.dreamkas.model.Token;

public interface AuthRequestActivity
{
    void onAuthSuccessRequest(Token authResult);
    void onAuthFailureRequest(SpiceException spiceException);
}
