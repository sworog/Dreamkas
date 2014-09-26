package ru.crystals.vaverjanov.dreamkas.controller.listeners;

import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;

import ru.crystals.vaverjanov.dreamkas.model.NamedObject;
import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity;

public class GetStoresRequestListener implements RequestListener<NamedObjects>
{
    private final IStoresRequestHandler managedActivity;

    public GetStoresRequestListener(IStoresRequestHandler activity)
    {
        managedActivity = activity;
    }

    @Override
    public void onRequestSuccess(NamedObjects result)
    {
        //todo test
        //result = new NamedObjects();
        //result.add(new NamedObject("ololo","Fakename"));

        managedActivity.onGetStoresSuccessRequest(result);
    }

    @Override
    public void onRequestFailure(SpiceException spiceException)
    {
        managedActivity.onGetStoresFailureRequest(spiceException);
    }
}
