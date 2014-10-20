package ru.dreamkas.pos.view.fragments;

import android.view.View;
import android.widget.AdapterView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;
import com.octo.android.robospice.exception.RequestCancelledException;
import com.octo.android.robospice.persistence.exception.SpiceException;
import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;
import org.springframework.http.HttpStatus;
import org.springframework.web.client.HttpClientErrorException;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.Command;
import ru.dreamkas.pos.controller.PreferencesManager;
import ru.dreamkas.pos.adapters.NamedObjectSpinnerAdapter;
import ru.dreamkas.pos.controller.requests.AuthorisedRequestWrapper;
import ru.dreamkas.pos.controller.requests.GetStoresRequest;
import ru.dreamkas.pos.model.DrawerMenu;
import ru.dreamkas.pos.model.api.NamedObject;
import ru.dreamkas.pos.model.api.collections.NamedObjects;

@EFragment(R.layout.fragment_store)
public class StoreFragment extends AuthRequestsContainingFragment
{
    private PreferencesManager preferences;

    @ViewById
    public
    Spinner spStores;

    @ViewById
    TextView lblEmpty;

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
    public void onStop(){
        super.onStop();
    }

    void initStores(){
        storesRequestWrapped.init(getSpiceManager(), new GetStoresRequest(), getToken());
        storesRequestWrapped.execute(new GetStoresRequestSuccessFinishCommand(), new GetStoresRequestFailureFinishCommand());
        showProgressDialog(getActivity().getResources().getString(R.string.load_stores));

        spStores.setEmptyView(lblEmpty);
    }

    public class GetStoresRequestSuccessFinishCommand implements Command<NamedObjects>{
        public void execute(NamedObjects data){
            progressDialog.dismiss();
            setStoreSpinner(data);
        }
    }

    public class GetStoresRequestFailureFinishCommand implements Command<SpiceException>{
        public void execute(SpiceException spiceException){
            progressDialog.dismiss();

            String msg;
            if(spiceException.getCause() instanceof HttpClientErrorException){
                HttpClientErrorException exception = (HttpClientErrorException)spiceException.getCause();
                if(exception.getStatusCode().equals(HttpStatus.UNAUTHORIZED)){
                    //wrong credentials
                    msg = getResources().getString(R.string.error_unauthorized);
                }
                else{
                    //other Network exception
                    msg = spiceException.getMessage();
                }
            }
            else if(spiceException instanceof RequestCancelledException){
                //cancelled
                msg = spiceException.getMessage();
            }
            else{
                //other exception
                msg = spiceException.getMessage();
            }

            Toast.makeText(getActivity(), msg, Toast.LENGTH_LONG).show();
        }
    }

    @Click(R.id.btnSaveStoreSettings)
    void openKas(){
        changeFragmentCallback.onFragmentChange(DrawerMenu.AppStates.Kas);
    }

    private void setStoreSpinner(NamedObjects stores){
        NamedObjectSpinnerAdapter adapter = new NamedObjectSpinnerAdapter(getActivity(), android.R.layout.simple_spinner_item, android.R.layout.simple_spinner_dropdown_item, stores);

        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spStores.setAdapter(adapter);
        spStores.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int position, long id) {
                preferences.setCurrentStore(((NamedObject) spStores.getAdapter().getItem(position)).getId());
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });

        int currentStorePosition = adapter.getHintElementIndex();

        if (preferences.getCurrentStore() != null) {
            for (int i = 0; i < adapter.getCount(); i++) {
                if (stores.get(i).getId().equals(preferences.getCurrentStore())) {
                    currentStorePosition = i;
                    break;
                }
            }
        }
        spStores.setSelection(currentStorePosition);
    }
}
