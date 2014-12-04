package ru.dreamkas.pos.view.components;

import android.app.Activity;
import android.content.Context;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.widget.Toolbar;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.AttributeSet;
import android.util.Log;
import android.view.MotionEvent;
import android.view.View;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EViewGroup;
import org.androidannotations.annotations.ItemClick;
import org.androidannotations.annotations.ViewById;
import java.util.Timer;
import java.util.TimerTask;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.Command;
import ru.dreamkas.pos.adapters.ProductsAdapter;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.model.api.collections.Products;

@EViewGroup(R.layout.product_search_component)
public class ProductSearchComponent extends LinearLayout {
    private final static int THRESHOLD = 3;
    private final static int DELAY = 500;

    @ViewById
    ProgressBar pbSearchProduct;

    @ViewById
    ListView lvProductsSearchResult;

    @ViewById
    TextView lblSearchResultEmpty;

    @ViewById
    EditText txtProductSearchQuery;

    @ViewById
    Toolbar tbSearch;

    Command<String> mSearchCommand;
    Command<Product> mAddRecepietItemCommand;
    private Command<String> mNavigateToCommand;

    public ProductSearchComponent(Context context) {
        super(context);
    }

    public ProductSearchComponent(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public ProductSearchComponent(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
    }

    @AfterViews
    public void viewInit(){
        lvProductsSearchResult.setEmptyView(lblSearchResultEmpty);
        addEditTextChangeListeners();

        tbSearch.setLogo(R.drawable.ic_arrow_back);

        tbSearch.setOnTouchListener(new OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {
                if (event.getAction() == MotionEvent.ACTION_UP || event.getAction() == MotionEvent.ACTION_CANCEL) {
                    if (event.getRawX() <= tbSearch.findViewById(R.id.llToolbarChilds).getLeft()){
                        mNavigateToCommand.execute("0");
                        return true;
                    }
                }
                return false;
            }
        });
    }

    public void init(final Command<String> navigateToCommand, Command<String> searchCommand, Command<Product> addReceiptItemCommand){
        mSearchCommand = searchCommand;
        mAddRecepietItemCommand = addReceiptItemCommand;
        mNavigateToCommand = navigateToCommand;
    }

    @Click(R.id.btnSearchEditTextClear)
    void clear(){
        txtProductSearchQuery.setText("");
        setSearchResultToListView(null);
    }

    public void setSearchResultToListView(Products products) {
        if(products == null){
            products = new Products();
            lblSearchResultEmpty.setText(getResources().getString(R.string.msgSearchReq));
            lblSearchResultEmpty.setVisibility(View.VISIBLE);
        }else if(products.size() == 0){
            lblSearchResultEmpty.setText(getResources().getString(R.string.msgSearchEmptyResult));
            lblSearchResultEmpty.setVisibility(View.VISIBLE);
        }

        ProductsAdapter adapter = new ProductsAdapter(getContext(), R.layout.product_search_listview_item, products, txtProductSearchQuery.getText().toString());
        lvProductsSearchResult.setAdapter(adapter);

        pbSearchProduct.setVisibility(View.GONE);
    }

    @ItemClick
    void lvProductsSearchResultItemClicked(Product product) {
        mAddRecepietItemCommand.execute(product);
    }

    private void addEditTextChangeListeners(){
            txtProductSearchQuery.addTextChangedListener(new TextWatcher() {
            private final Timer timer = new Timer();
            private TimerTask lastTimer;

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {}

            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

            @Override
            public void afterTextChanged(Editable s) {
                if(lastTimer != null) {
                    lastTimer.cancel();
                }

                if(s.length() >= THRESHOLD) {
                    lastTimer = new TimerTask() {
                        @Override
                        public void run() {
                            String query = txtProductSearchQuery.getText().toString();

                            if(query.length() >= THRESHOLD) {
                                pbSearchProduct.post(new Runnable() {
                                        @Override
                                        public void run() {
                                            pbSearchProduct.setVisibility(View.VISIBLE);
                                            lblSearchResultEmpty.setVisibility(View.GONE);
                                        }
                                    });

                                mSearchCommand.execute(query);
                            }
                        }
                    };
                    timer.schedule(lastTimer, DELAY);
                }else {
                    setSearchResultToListView(null);
                }
            }
        });
    }
}
