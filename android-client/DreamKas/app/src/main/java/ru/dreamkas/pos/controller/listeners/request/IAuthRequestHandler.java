package ru.dreamkas.pos.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;
import ru.dreamkas.pos.model.api.Token;

public interface IAuthRequestHandler{
    void onAuthSuccessRequest(Token authResult);
    void onAuthFailureRequest(SpiceException spiceException);
}
