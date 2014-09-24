package ru.crystals.vaverjanov.dreamkas.controller.listeners.request;

import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity;

public class GetGroupsRequestListener implements RequestListener<NamedObjects> {

    private final LighthouseDemoActivity managedActivity;

    public GetGroupsRequestListener(LighthouseDemoActivity activity)
    {
        managedActivity = activity;
    }

    @Override
    public void onRequestSuccess(NamedObjects result)
    {
        //do some logic here
        //managedActivity.onGetGroupsSuccessRequest(result);
    }

    @Override
    public void onRequestFailure(SpiceException spiceException)
    {
//        managedActivity.onGetGroupsFailureRequest(spiceException);
    }
}