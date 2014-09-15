package ru.crystals.vaverjanov.dreamkas.view;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Bundle;
import android.app.Fragment;
//import android.support.v4.app.Fragment;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.Toast;

import com.octo.android.robospice.SpiceManager;
import com.octo.android.robospice.persistence.DurationInMillis;
import com.octo.android.robospice.persistence.exception.SpiceException;
import com.octo.android.robospice.request.listener.RequestListener;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.GetGroupsRequest;
import ru.crystals.vaverjanov.dreamkas.controller.LighthouseSpiceService;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.AuthRequestListener;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.GetGroupsRequestListener;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.GetStoresRequestListener;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.IStoresRequestHandler;
import ru.crystals.vaverjanov.dreamkas.controller.GetStoresRequest;
import ru.crystals.vaverjanov.dreamkas.model.NamedObject;
import ru.crystals.vaverjanov.dreamkas.model.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.view.adapters.NamedObjectSpinnerAdapter;
import ru.crystals.vaverjanov.dreamkas.view.adapters.NamedObjectsAdapter;

@EFragment(R.layout.fragment_store)
public class StoreFragment extends BaseFragment implements IStoresRequestHandler
{
    @ViewById
    Spinner spStores;

    @ViewById
    Button btnSaveStoreSettings;

    @Bean
    public GetStoresRequest storesRequest;


    private final GetStoresRequestListener storesRequestListener = new GetStoresRequestListener(this);

    @Override
    public void onStart()
    {
        super.onStart();
    }

    @Override
    public void onStop()
    {
        super.onStop();
    }

    @AfterViews
    void initStores()
    {
        storesRequest.setToken(((LighthouseDemoActivity)getActivity()).getToken());
        changeFragmentCallback.getRestClient().execute(storesRequest, null, DurationInMillis.NEVER, storesRequestListener);
        showProgressDialog("Загрузка магазинов");
    }

    @Click(R.id.btnSaveStoreSettings)
    void openKas()
    {
        changeFragmentCallback.onFragmentChange(KasFragments.Kas);
    }

    @Override
    public void onGetStoresSuccessRequest(NamedObjects stores)
    {
        progressDialog.dismiss();
        setStoreSpinner(stores);
    }

    private void setStoreSpinner(NamedObjects stores)
    {
        ArrayAdapter<NamedObject> adapter = new NamedObjectSpinnerAdapter(getActivity(), android.R.layout.simple_spinner_item, android.R.layout.simple_spinner_dropdown_item, stores);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spStores.setAdapter(adapter);
        spStores.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener()
        {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l)
            {
                SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(getActivity());
                SharedPreferences.Editor editor = preferences.edit();
                editor.putString(getResources().getString(R.string.current_store_id), ((NamedObject)spStores.getAdapter().getItem(i)).getId());
                editor.apply();
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView)
            {

            }
        });

        int currentStorePosition = 0;
        SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(getActivity());
        String currentStoreId = preferences.getString(getResources().getString(R.string.current_store_id), "");

        for(int i = 0; i < stores.size(); i++)
        {
            if(stores.get(i).getId().equals(currentStoreId))
            {
                currentStorePosition = i;
                break;
            }
        }

        spStores.setSelection(currentStorePosition);
    }

    @Override
    public void onGetStoresFailureRequest(SpiceException spiceException)
    {
        progressDialog.dismiss();
        Toast.makeText(getActivity(), spiceException.getMessage(), Toast.LENGTH_LONG);
    }
}
