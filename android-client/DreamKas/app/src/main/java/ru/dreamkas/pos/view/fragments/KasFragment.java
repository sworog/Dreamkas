package ru.dreamkas.pos.view.fragments;

import android.os.Bundle;
import android.widget.Toast;

import com.octo.android.robospice.exception.RequestCancelledException;
import com.octo.android.robospice.persistence.exception.SpiceException;

import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;
import org.springframework.http.HttpStatus;
import org.springframework.web.client.HttpClientErrorException;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.Command;
import ru.dreamkas.pos.controller.PreferencesManager;
import ru.dreamkas.pos.controller.requests.AuthorisedRequestWrapper;
import ru.dreamkas.pos.controller.requests.GetStoreRequest;
import ru.dreamkas.pos.controller.requests.SearchProductsRequest;
import ru.dreamkas.pos.model.api.NamedObject;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.model.api.collections.Products;
import ru.dreamkas.pos.view.components.KasMainBlockComponent;
import ru.dreamkas.pos.view.components.ProductSearchComponent;
import ru.dreamkas.pos.view.components.ReceiptComponent;

@EFragment(R.layout.fragment_kas)
public class KasFragment extends AuthRequestsContainingFragment{
    private PreferencesManager preferences;

    @Bean
    protected AuthorisedRequestWrapper searchProductsRequestWrapped;

    @Bean
    protected AuthorisedRequestWrapper getStoreRequestWrapped;

    @ViewById
    KasMainBlockComponent cKasMainBlock;

    @ViewById
    ReceiptComponent ccReceipt;


    @Override
    public void onCreate(Bundle bundle){
        super.onCreate(bundle);
        preferences = PreferencesManager.getInstance();
    }

    @Override
    public void onStart(){
        super.onStart();
        loadStoreInfo();

        cKasMainBlock.init(new SearchProductsCommand(), new AddProductToReceiptCommand());
    }

    public class AddProductToReceiptCommand implements Command<Product>{
        public void execute(Product product){
            ccReceipt.add(product);
        }
    }

    private void loadStoreInfo() {
        GetStoreRequest request = new GetStoreRequest();
        request.setStoreId(preferences.getCurrentStore());
        getStoreRequestWrapped.init(getSpiceManager(), request, getToken());
        getStoreRequestWrapped.execute(new GetStoreRequestSuccessFinishCommand(), new GetStoreRequestFailureFinishCommand());
    }

    public class SearchProductsCommand implements Command<String>{
        public void execute(String query){
            SearchProductsRequest request = new SearchProductsRequest();
            request.setQuery(query);
            searchProductsRequestWrapped.init(getSpiceManager(), request, getToken());
            searchProductsRequestWrapped.execute(new SearchProductsRequestSuccessFinishCommand(), new SearchProductsRequestFailureFinishCommand());
        }
    }

    public class SearchProductsRequestSuccessFinishCommand implements Command<Products>{
        public void execute(Products data)
        {
            cKasMainBlock.getSearchProductComponent().setSearchResultToListView(data);
        }
    }

    public class SearchProductsRequestFailureFinishCommand implements Command<SpiceException>{
        public void execute(SpiceException spiceException){
            cKasMainBlock.getSearchProductComponent().setSearchResultToListView(null);
            showRequestErrorToast(spiceException);
        }
    }

    public class GetStoreRequestSuccessFinishCommand implements Command<NamedObject>{
        public void execute(NamedObject data){
            //getActivity().getActionBar().setTitle(data.getName());
        }
    }

    public class GetStoreRequestFailureFinishCommand implements Command<SpiceException>{
        public void execute(SpiceException spiceException){
            cKasMainBlock.getSearchProductComponent().setSearchResultToListView(null);
            showRequestErrorToast(spiceException);
        }
    }

    private void showRequestErrorToast(SpiceException spiceException){
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
