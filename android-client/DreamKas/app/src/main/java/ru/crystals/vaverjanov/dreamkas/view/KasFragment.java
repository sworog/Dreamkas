package ru.crystals.vaverjanov.dreamkas.view;

import android.os.Bundle;
import android.widget.Toast;
import com.octo.android.robospice.exception.RequestCancelledException;
import com.octo.android.robospice.persistence.exception.SpiceException;
import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;
import org.springframework.http.HttpStatus;
import org.springframework.web.client.HttpClientErrorException;
import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.Command;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.controller.requests.AuthorisedRequestWrapper;
import ru.crystals.vaverjanov.dreamkas.controller.requests.GetStoreRequest;
import ru.crystals.vaverjanov.dreamkas.controller.requests.SearchProductsRequest;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;
import ru.crystals.vaverjanov.dreamkas.model.api.collections.Products;

@EFragment(R.layout.fragment_kas)
public class KasFragment extends BaseFragment
{
    private PreferencesManager preferences;

    @Bean
    protected AuthorisedRequestWrapper searchProductsRequestWrapped;

    @Bean
    protected AuthorisedRequestWrapper getStoreRequestWrapped;



    @ViewById
    ProductSearchComponent scProducts;

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

        loadStoreInfo();



        scProducts.init(new SearchProductsCommand());
    }

    private void loadStoreInfo() {
        GetStoreRequest request = new GetStoreRequest();
        request.setStoreId(preferences.getCurrentStore());
        getStoreRequestWrapped.init(changeFragmentCallback.getRestClient(), request, ((LighthouseDemoActivity) getActivity()).getToken());
        getStoreRequestWrapped.execute(new GetStoreRequestSuccessFinishCommand(), new GetStoreRequestFailureFinishCommand());
    }

    @AfterViews
    public void onAfterViews()
    {

    }




    public class SearchProductsCommand implements Command<String>
    {
        public void execute(String query)
        {
            SearchProductsRequest request = new SearchProductsRequest();
            request.setQuery(query);
            searchProductsRequestWrapped.init(changeFragmentCallback.getRestClient(), request, ((LighthouseDemoActivity) getActivity()).getToken());
            searchProductsRequestWrapped.execute(new SearchProductsRequestSuccessFinishCommand(), new SearchProductsRequestFailureFinishCommand());
        }
    }

    public class SearchProductsRequestSuccessFinishCommand implements Command<Products>
    {
        public void execute(Products data)
        {
            scProducts.setSearchResultToListView(data);
        }
    }

    public class SearchProductsRequestFailureFinishCommand implements Command<SpiceException>
    {
        public void execute(SpiceException spiceException)
        {
            scProducts.setSearchResultToListView(null);
            showRequestErrorToast(spiceException);
        }
    }

    public class GetStoreRequestSuccessFinishCommand implements Command<NamedObject>
    {
        public void execute(NamedObject data)
        {
            getActivity().getActionBar().setTitle(data.getName());
        }
    }

    public class GetStoreRequestFailureFinishCommand implements Command<SpiceException>
    {
        public void execute(SpiceException spiceException)
        {
            scProducts.setSearchResultToListView(null);
            showRequestErrorToast(spiceException);
        }
    }

    private void showRequestErrorToast(SpiceException spiceException){
        String msg;
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
