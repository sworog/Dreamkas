package ru.dreamkas.pos.view.components;

import android.app.Activity;
import android.content.Context;
import android.util.AttributeSet;
import android.widget.LinearLayout;

import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EViewGroup;
import org.androidannotations.annotations.ViewById;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.Command;
import ru.dreamkas.pos.controller.requests.SearchProductsRequest;
import ru.dreamkas.pos.model.api.Product;

@EViewGroup(R.layout.kas_main_block)
public class KasMainBlockComponent extends LinearLayout {

    @ViewById
    HorizontalPager pagerKasMainBlock;

    //@ViewById
    //ProductSearchComponent cProductSearch;

    @ViewById
    CatalogComponent cCatalog;
    private ProductSearchComponent cProductSearch;
    private Command<String> mSearchCommand;
    private Command<Product> mAddReceiptItemCommand;

    public KasMainBlockComponent(Context context) {

        super(context);
    }

    public KasMainBlockComponent(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public KasMainBlockComponent(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
    }

    public void init(Command<String> searchCommand, Command<Product> addReceiptItemCommand){
        mSearchCommand = searchCommand;
        mAddReceiptItemCommand = addReceiptItemCommand;
        cCatalog.init(new NavigateCommand());
        cProductSearch = (ProductSearchComponent)findViewById(R.id.cProductSearchComponent);
        cProductSearch.init(new NavigateCommand(), mSearchCommand, mAddReceiptItemCommand);

        pagerKasMainBlock.allowTouchControl = false;

        pagerKasMainBlock.setOnScreenSwitchListener(new HorizontalPager.OnScreenSwitchListener() {
            @Override
            public void onScreenSwitched(int screen) {
                if(screen == 0){
                    if(cProductSearch!= null){
                        //pagerKasMainBlock.removeViewAt(1);
                        //cProductSearch = null;
                    }
                }
            }
        });
    }

    public ProductSearchComponent getSearchProductComponent() {
        return cProductSearch;
    }

    public class NavigateCommand implements Command<String>{
        public void execute(String navigateTo){
            if(navigateTo.equals("1")){
                /*cProductSearch = ProductSearchComponent_.build(getContext());
                cProductSearch.setLayoutParams(new LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.MATCH_PARENT));
                pagerKasMainBlock.addView(cProductSearch);
                cProductSearch.init(new NavigateCommand(), mSearchCommand, mAddReceiptItemCommand);*/

                pagerKasMainBlock.setCurrentScreen(1, true);
            }else {
                pagerKasMainBlock.setCurrentScreen(0, true);
            }
        }
    }
}
