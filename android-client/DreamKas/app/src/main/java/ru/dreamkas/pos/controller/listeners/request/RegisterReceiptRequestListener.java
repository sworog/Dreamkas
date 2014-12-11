package ru.dreamkas.pos.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;

import ru.dreamkas.pos.controller.Command;

public class RegisterReceiptRequestListener<T> extends ExtRequestListener<T>
{
    private final Command<T> mSuccessFinishcommand;
    private final Command<SpiceException> mFailureFinishCommand;

    public RegisterReceiptRequestListener(Command<T> SuccessFinishCommand, Command<SpiceException> FailureFinishCommand){
        mSuccessFinishcommand = SuccessFinishCommand;
        mFailureFinishCommand = FailureFinishCommand;
    }

    @Override
    public void onRequestSuccess(T result){
        mSuccessFinishcommand.execute(result);
        super.onRequestSuccess(result);
    }

    @Override
    public void onRequestFailure(SpiceException spiceException){
        mFailureFinishCommand.execute(spiceException);
        super.onRequestFailure(spiceException);
    }
}
