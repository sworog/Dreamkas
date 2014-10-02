package ru.crystals.vaverjanov.dreamkas.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;

import ru.crystals.vaverjanov.dreamkas.controller.Command;

public class GetStoresRequestListener<T> extends ExtRequestListener<T>
{
    private final Command<T> mSuccessFinishcommand;
    private final Command<SpiceException> mFailureFinishCommand;

    public GetStoresRequestListener(Command<T> SuccessFinishCommand, Command<SpiceException> FailureFinishCommand)
    {
        mSuccessFinishcommand = SuccessFinishCommand;
        mFailureFinishCommand = FailureFinishCommand;
    }

    @Override
    public void onRequestSuccess(T result)
    {
        mSuccessFinishcommand.execute(result);
        super.onRequestSuccess(result);
    }

    @Override
    public void onRequestFailure(SpiceException spiceException)
    {
        mFailureFinishCommand.execute(spiceException);
        super.onRequestFailure(spiceException);
    }
}
