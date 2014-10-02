package ru.crystals.vaverjanov.dreamkas.view;

import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.Toast;

import com.octo.android.robospice.exception.RequestCancelledException;
import com.octo.android.robospice.persistence.exception.SpiceException;
import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;
import org.springframework.http.HttpStatus;
import org.springframework.web.client.HttpClientErrorException;

import java.util.List;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.Command;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.controller.adapters.NamedObjectSpinnerAdapter;
import ru.crystals.vaverjanov.dreamkas.controller.requests.AuthorisedRequestWrapper;
import ru.crystals.vaverjanov.dreamkas.controller.requests.GetStoresRequest;
import ru.crystals.vaverjanov.dreamkas.model.DrawerMenu;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;
import ru.crystals.vaverjanov.dreamkas.model.api.collections.NamedObjects;

@EFragment(R.layout.fragment_store)
public class StoreFragment extends BaseFragment
{
    private static final int NONE_STORE_SELECTED_INDEX = -1;
    private PreferencesManager preferences;

    @ViewById
    public
    Spinner spStores;

    @ViewById
    Button btnSaveStoreSettings;

    @Bean
    public AuthorisedRequestWrapper storesRequestWrapped;

    @Override
    public void onStart()
    {
        super.onStart();

        preferences = PreferencesManager.getInstance();

        initStores();
    }

    @Override
    public void onStop()
    {
        super.onStop();
    }

    //@AfterViews
    void initStores()
    {
        storesRequestWrapped.init(changeFragmentCallback.getRestClient(), new GetStoresRequest(), ((LighthouseDemoActivity) getActivity()).getToken());
        storesRequestWrapped.execute(new GetStoresRequestSuccessFinishCommand(), new GetStoresRequestFailureFinishCommand());
        showProgressDialog(getActivity().getResources().getString(R.string.load_stores));
        View empty = getActivity().findViewById(R.id.empty1);
        spStores.setEmptyView(empty);
    }

    public class GetStoresRequestSuccessFinishCommand implements Command<NamedObjects>
    {
        public void execute(NamedObjects data)
        {
            progressDialog.dismiss();
            setStoreSpinner(data);
        }
    }

    public class GetStoresRequestFailureFinishCommand implements Command<SpiceException>
    {
        public void execute(SpiceException spiceException)
        {
            progressDialog.dismiss();

            String msg = "";
            if(spiceException.getCause() instanceof HttpClientErrorException)
            {
                HttpClientErrorException exception = (HttpClientErrorException)spiceException.getCause();
                if(exception.getStatusCode().equals(HttpStatus.UNAUTHORIZED))
                {
                    //wrong credentials
                    msg = getResources().getString(R.string.error_unauthorized);
                }
                else
                {
                    //other Network exception
                    msg = spiceException.getMessage();
                }
            }
            else if(spiceException instanceof RequestCancelledException)
            {
                //cancelled
                msg = spiceException.getMessage();
            }
            else
            {
                //other exception
                msg = spiceException.getMessage();
            }

            Toast.makeText(getActivity(), msg, Toast.LENGTH_LONG).show();
        }
    }

    @Click(R.id.btnSaveStoreSettings)
    void openKas()
    {
        changeFragmentCallback.onFragmentChange(DrawerMenu.AppStates.Kas);
    }

    private void setStoreSpinner(NamedObjects stores)
    {
        NamedObjectSpinnerAdapter adapter = new NamedObjectSpinnerAdapter(getActivity(), android.R.layout.simple_spinner_item, android.R.layout.simple_spinner_dropdown_item, stores);
        stores = new NamedObjects(adapter.getItems());

        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spStores.setAdapter(adapter);
        spStores.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener()
        {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int position, long id)
            {
                if(position > 0){
                    preferences.setCurrentStore(((NamedObject)spStores.getAdapter().getItem(position)).getId());
                }else{
                    preferences.removeCurrentStore();
                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView)
            {

            }
        });

        int currentStorePosition = NONE_STORE_SELECTED_INDEX;

        for(int i = 0; i < stores.size(); i++)
        {
            if(stores.get(i).getId().equals(preferences.getCurrentStore()))
            {
                currentStorePosition = i;
                break;
            }
        }

        spStores.setSelection(++currentStorePosition);
    }
}
