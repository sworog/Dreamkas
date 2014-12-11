package ru.dreamkas.pos.view.components;

import android.app.Activity;
import android.content.Context;
import android.support.v7.widget.Toolbar;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import org.androidannotations.annotations.Click;
import org.androidannotations.annotations.EViewGroup;
import org.androidannotations.annotations.ItemClick;
import org.androidannotations.annotations.ViewById;

import java.util.Timer;
import java.util.TimerTask;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.adapters.ProductsAdapter;
import ru.dreamkas.pos.controller.Command;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.model.api.collections.Products;

@EViewGroup(R.layout.catalog_component)
public class CatalogComponent extends LinearLayout {

    private Command<String> mNavigateCommand;

    public CatalogComponent(Context context) {
        super(context);
    }

    public CatalogComponent(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public CatalogComponent(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
    }

    public void init(Command<String> navigateCommand){
        mNavigateCommand = navigateCommand;
    }

    @Click(R.id.btnSearch)
    public void navigateToSearch(){
        mNavigateCommand.execute("1");
    }


    @Click(R.id.btnRestorePassword)
    public void navigateToSearch1(){
        mNavigateCommand.execute("1");
    }
}
