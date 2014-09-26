package ru.crystals.vaverjanov.dreamkas.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObjects;

public class GetStoresRequestListener extends ExtRequestListener<NamedObjects>
{
    private final IStoresRequestHandler managedActivity;

    public GetStoresRequestListener(IStoresRequestHandler activity)
    {
        managedActivity = activity;
    }

    @Override
    public void onRequestSuccess(NamedObjects result)
    {
        managedActivity.onGetStoresSuccessRequest(result);
        super.onRequestSuccess(result);
    }

    @Override
    public void onRequestFailure(SpiceException spiceException)
    {
        managedActivity.onGetStoresFailureRequest(spiceException);
        super.onRequestFailure(spiceException);
    }
}
