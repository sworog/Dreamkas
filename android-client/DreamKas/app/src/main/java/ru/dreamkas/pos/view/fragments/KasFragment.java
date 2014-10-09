package ru.dreamkas.pos.view.fragments;

import android.os.Bundle;
import android.widget.Button;
import android.widget.HeaderViewListAdapter;
import android.widget.ListView;
import android.widget.Toast;

import com.octo.android.robospice.exception.RequestCancelledException;
import com.octo.android.robospice.persistence.exception.SpiceException;
import org.androidannotations.annotations.Bean;
import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;
import org.springframework.http.HttpStatus;
import org.springframework.web.client.HttpClientErrorException;

import java.util.ArrayList;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.adapters.ProductsAdapter;
import ru.dreamkas.pos.adapters.ReceiptAdapter;
import ru.dreamkas.pos.controller.Command;
import ru.dreamkas.pos.controller.PreferencesManager;
import ru.dreamkas.pos.controller.requests.AuthorisedRequestWrapper;
import ru.dreamkas.pos.controller.requests.GetStoreRequest;
import ru.dreamkas.pos.controller.requests.SearchProductsRequest;
import ru.dreamkas.pos.model.api.NamedObject;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.model.api.collections.Products;
import ru.dreamkas.pos.view.components.ProductSearchComponent;

@EFragment(R.layout.fragment_kas)
public class KasFragment extends AuthRequestsContainingFragment{
    private PreferencesManager preferences;

    @Bean
    protected AuthorisedRequestWrapper searchProductsRequestWrapped;

    @Bean
    protected AuthorisedRequestWrapper getStoreRequestWrapped;

    @ViewById
    ProductSearchComponent scProducts;

    @ViewById
    ListView lvReceipt;

    @ViewById
    Button btnReceiptClear;

    private ArrayList<Product> mReceipt;



    @Override
    public void onCreate(Bundle bundle){
        super.onCreate(bundle);
        preferences = PreferencesManager.getInstance();
        receiptInit();
    }

    @Override
    public void onStart(){
        super.onStart();
        loadStoreInfo();
        scProducts.init(new SearchProductsCommand(), new AddProductToReceiptCommand());
    }

    private void receiptInit() {
        mReceipt = new ArrayList<Product>();
        ProductsAdapter adapter = new ReceiptAdapter(getActivity(), R.layout.receipt_listview_item, mReceipt);
        lvReceipt.setAdapter(adapter);

        //Button btnLoadMore = new Button(getActivity());
        //btnLoadMore.setText("Очистить чек");
        lvReceipt.addFooterView(btnReceiptClear);
    }

    private void loadStoreInfo() {
        GetStoreRequest request = new GetStoreRequest();
        request.setStoreId(preferences.getCurrentStore());
        getStoreRequestWrapped.init(getSpiceManager(), request, getToken());
        getStoreRequestWrapped.execute(new GetStoreRequestSuccessFinishCommand(), new GetStoreRequestFailureFinishCommand());
    }

    private void scrollMyListViewToBottom() {
        lvReceipt.post(new Runnable() {
            @Override
            public void run() {
                lvReceipt.setSelection(mReceipt.size() - 1);
            }
        });
    }

    public class AddProductToReceiptCommand implements Command<Product>{
        public void execute(Product product){
            mReceipt.add(product);
            ((ReceiptAdapter)((HeaderViewListAdapter)lvReceipt.getAdapter()).getWrappedAdapter()).notifyDataSetChanged();
            scrollMyListViewToBottom();
        }
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
            scProducts.setSearchResultToListView(data);
        }
    }

    public class SearchProductsRequestFailureFinishCommand implements Command<SpiceException>{
        public void execute(SpiceException spiceException){
            scProducts.setSearchResultToListView(null);
            showRequestErrorToast(spiceException);
        }
    }

    public class GetStoreRequestSuccessFinishCommand implements Command<NamedObject>{
        public void execute(NamedObject data){
            getActivity().getActionBar().setTitle(data.getName());
        }
    }

    public class GetStoreRequestFailureFinishCommand implements Command<SpiceException>{
        public void execute(SpiceException spiceException){
            scProducts.setSearchResultToListView(null);
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
