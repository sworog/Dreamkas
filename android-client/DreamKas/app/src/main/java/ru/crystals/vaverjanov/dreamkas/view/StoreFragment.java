package ru.crystals.vaverjanov.dreamkas.view;

import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.Toast;
import com.octo.android.robospice.persistence.DurationInMillis;
import com.octo.android.robospice.persistence.exception.SpiceException;
import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;
import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.controller.adapters.NamedObjectSpinnerAdapter;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.request.GetStoresRequestListener;
import ru.crystals.vaverjanov.dreamkas.controller.listeners.request.IStoresRequestHandler;
import ru.crystals.vaverjanov.dreamkas.controller.requests.GetStoresRequest;
import ru.crystals.vaverjanov.dreamkas.model.DrawerMenu;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObjects;

@EFragment(R.layout.fragment_store)
public class StoreFragment extends BaseFragment implements IStoresRequestHandler
{
    private PreferencesManager preferences;

    @ViewById
    public
    Spinner spStores;

    @ViewById
    Button btnSaveStoreSettings;

    @Bean
    public GetStoresRequest storesRequest;


    public final GetStoresRequestListener storesRequestListener = new GetStoresRequestListener(this);

    @Override
    public void onStart()
    {
        super.onStart();

        preferences = PreferencesManager.getInstance();

        initStores();
        //initStores();
    }

    @Override
    public void onStop()
    {
        super.onStop();
    }

    //@AfterViews
    void initStores()
    {
        storesRequest.setToken(((LighthouseDemoActivity) getActivity()).getToken());
        changeFragmentCallback.getRestClient().execute(storesRequest, null, DurationInMillis.NEVER, storesRequestListener);
        //todo make requestStarted call by storesRequest or something
        storesRequestListener.requestStarted();
        showProgressDialog(getActivity().getResources().getString(R.string.load_stores));

        View empty = getActivity().findViewById(R.id.empty1);
        spStores.setEmptyView(empty);
    }

    @Click(R.id.btnSaveStoreSettings)
    void openKas()
    {
        changeFragmentCallback.onFragmentChange(DrawerMenu.AppStates.Kas);
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
                preferences.setCurrentStore(((NamedObject)spStores.getAdapter().getItem(i)).getId());
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView)
            {

            }
        });

        int currentStorePosition = 0;

        for(int i = 0; i < stores.size(); i++)
        {
            if(stores.get(i).getId().equals(preferences.getCurrentStore()))
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
        Toast.makeText(getActivity(), spiceException.getMessage(), Toast.LENGTH_LONG).show();
    }
}
