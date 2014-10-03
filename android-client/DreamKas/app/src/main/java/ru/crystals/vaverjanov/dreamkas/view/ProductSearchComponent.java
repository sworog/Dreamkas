package ru.crystals.vaverjanov.dreamkas.view;

import android.app.Activity;
import android.content.Context;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.AttributeSet;
import android.view.View;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import org.androidannotations.annotations.EViewGroup;
import org.androidannotations.annotations.ViewById;
import java.util.Timer;
import java.util.TimerTask;
import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.Command;
import ru.crystals.vaverjanov.dreamkas.controller.adapters.ProductsAdapter;
import ru.crystals.vaverjanov.dreamkas.model.api.collections.Products;

@EViewGroup(R.layout.product_search_component)
public class ProductSearchComponent extends LinearLayout {
    @ViewById
    ProgressBar pbSearchProduct;

    @ViewById
    ListView lvProductsSearchResult;

    @ViewById
    TextView lblSearchResultEmpty;

    @ViewById
    EditText txtProductSearchQuery;

    Command<String> mSearchCommand;
    private final Activity mContext;

    public ProductSearchComponent(Context context) {
        super(context);
        this.mContext = (Activity)context;
    }

    public ProductSearchComponent(Context context, AttributeSet attrs) {
        super(context, attrs);
        this.mContext = (Activity)context;
    }

    public ProductSearchComponent(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        this.mContext = (Activity)context;
    }

    public void init(Command<String> searchCommand)
    {
        mSearchCommand = searchCommand;
        lvProductsSearchResult.setEmptyView(lblSearchResultEmpty);
        addEditTextChangeListeners();
    }

    public void setSearchResultToListView(Products products) {
        ProductsAdapter adapter = new ProductsAdapter(mContext, R.layout.arrow_listview_item, products);

        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        lvProductsSearchResult.setAdapter(adapter);
        lvProductsSearchResult.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> adapterView, View view, int position, long id) {
                //todo execute command on click
            }

            @Override
            public void onNothingSelected(AdapterView<?> adapterView) {

            }
        });
        pbSearchProduct.setVisibility(View.GONE);
    }

    private void addEditTextChangeListeners()
    {
            txtProductSearchQuery.addTextChangedListener(new TextWatcher() {

            private final int threshold = 2;
            private final int delay = 1000;

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

                if(s.length() > threshold) {
                    lastTimer = new TimerTask() {
                        @Override
                        public void run() {
                            String query = txtProductSearchQuery.getText().toString();

                            if(query.length() >= threshold) {

                                mContext.runOnUiThread(new Runnable() {
                                    @Override
                                    public void run() {
                                        pbSearchProduct.setVisibility(View.VISIBLE);
                                    }
                                });

                                mSearchCommand.execute(query);
                            }
                        }
                    };
                    timer.schedule(lastTimer, delay);
                }
            }
        });
    }


}
