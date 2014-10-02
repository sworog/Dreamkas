package ru.crystals.vaverjanov.dreamkas.view;

import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.ListView;
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

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.Command;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.controller.requests.AuthorisedRequestWrapper;
import ru.crystals.vaverjanov.dreamkas.controller.requests.GetStoresRequest;
import ru.crystals.vaverjanov.dreamkas.controller.requests.SearchProductsRequest;
import ru.crystals.vaverjanov.dreamkas.model.api.collections.NamedObjects;
import ru.crystals.vaverjanov.dreamkas.model.api.collections.Products;

@EFragment(R.layout.fragment_kas)
public class KasFragment extends BaseFragment
{
    private PreferencesManager preferences;

    @Bean
    protected AuthorisedRequestWrapper searchProductsRequestWrapped;

    @ViewById
    TextView lblStore;

    @ViewById
    ListView lvProductsSearchResult;

    @ViewById
    EditText txtProductSearchQuery;

    @Override
    public void onCreate(Bundle bundle)
    {
        super.onCreate(bundle);
        preferences = PreferencesManager.getInstance();
    }

    @Override
    public void onStart()
    {
        super.onStart();

        lblStore.setText(preferences.getCurrentStore());


        View empty = getActivity().findViewById(R.id.empty1);
        lvProductsSearchResult.setEmptyView(empty);
    }

    @Click(R.id.btnSearchProducts)
    void searchProducts(){
        SearchProductsRequest request = new SearchProductsRequest();
        request.setQuery(txtProductSearchQuery.getText());
        searchProductsRequestWrapped.init(changeFragmentCallback.getRestClient(), request, ((LighthouseDemoActivity) getActivity()).getToken());
        searchProductsRequestWrapped.execute(new SearchProductsRequestSuccessFinishCommand(), new SearchProductsRequestFailureFinishCommand());
        showProgressDialog(getActivity().getResources().getString(R.string.load_stores));
    }

    public class SearchProductsRequestSuccessFinishCommand implements Command<Products>
    {
        public void execute(Products data)
        {
            progressDialog.dismiss();
            //setStoreSpinner(data);
        }
    }

    public class SearchProductsRequestFailureFinishCommand implements Command<SpiceException>
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
}
