package ru.crystals.vaverjanov.dreamkas.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;
import ru.crystals.vaverjanov.dreamkas.model.api.Token;

public interface IAuthRequestHandler
{
    void onAuthSuccessRequest(Token authResult);
    void onAuthFailureRequest(SpiceException spiceException);
}
