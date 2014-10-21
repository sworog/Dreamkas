package ru.dreamkas.pos.view.activities;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Fragment;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Typeface;
import android.os.Bundle;
import android.support.v4.app.ActionBarDrawerToggle;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.text.SpannableStringBuilder;
import android.view.MenuItem;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Toast;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ItemClick;
import org.androidannotations.annotations.ViewById;
import org.springframework.util.StringUtils;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.controller.RestFragmentContainer;
import ru.dreamkas.pos.controller.PreferencesManager;
import ru.dreamkas.pos.model.DrawerMenu;
import ru.dreamkas.pos.view.fragments.KasFragment_;
import ru.dreamkas.pos.view.fragments.StoreFragment_;

@EActivity(R.layout.main_activity)
public class MainActivity extends Activity implements RestFragmentContainer{
    @ViewById
    DrawerLayout drawer_layout;

    @ViewById
    ListView lstDrawer;

    ActionBarDrawerToggle drawerToggle;

    private String mToken;
    private Fragment mCurrentFragment;

    @Override
    protected void onCreate(Bundle savedInstanceState){
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main_activity);

        Intent intent = getIntent();
        mToken = intent.getStringExtra("access_token");





    }

    @AfterViews
    void initDrawer(){
        drawer_layout.setDrawerShadow(R.drawable.drawer_shadow, GravityCompat.START);

        lstDrawer.setAdapter(new ArrayAdapter<String>(this, R.layout.drawer_list_item, DrawerMenu.getMenuItems().values().toArray(new String[DrawerMenu.getMenuItems().size()])));
        lstDrawer.setChoiceMode(ListView.CHOICE_MODE_SINGLE);
        lstDrawer.setSelection(0);

        getActionBar().setDisplayHomeAsUpEnabled(true);
        getActionBar().setHomeButtonEnabled(true);
        getActionBar().setTitle(getResources().getString(R.string.dream_kas_title));
        drawerToggle = new ActionBarDrawerToggle(this, drawer_layout, R.drawable.ic_drawer, R.string.drawer_open, R.string.drawer_close){
            private CharSequence mPrevTitle;
            public void onDrawerClosed(View view){
                getActionBar().setTitle(mPrevTitle);
                invalidateOptionsMenu();
            }

            public void onDrawerOpened(View drawerView){
                mPrevTitle = getActionBar().getTitle();
                getActionBar().setTitle(getResources().getString(R.string.drawer_title));
                invalidateOptionsMenu();
            }
        };
        drawer_layout.setDrawerListener(drawerToggle);

        drawerToggle.syncState();

        changeState(DrawerMenu.AppStates.Kas);

    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item){
        if (drawerToggle.onOptionsItemSelected(item)){
            return true;
        }

        switch(item.getItemId()){
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    @ItemClick
    void lstDrawerItemClicked(int position){
        lstDrawer.setSelection(position);
        changeState(DrawerMenu.AppStates.fromValue(position));
        drawer_layout.closeDrawer(lstDrawer);
    }

    public void changeState(final DrawerMenu.AppStates state)
    {
        switch (state){
            case Kas:
                if(!StringUtils.hasText(PreferencesManager.getInstance().getCurrentStore())){
                    changeState(DrawerMenu.AppStates.Store);
                    Toast.makeText(this, getResources().getString(R.string.error_open_kas_without_store), Toast.LENGTH_LONG).show();
                    return;
                }

                mCurrentFragment = new KasFragment_();
                putTokenToFragment(mCurrentFragment);
                displayCurrentFragment(mCurrentFragment, String.valueOf(state.getValue()));
                break;
            case Store:
                mCurrentFragment = new StoreFragment_();
                putTokenToFragment(mCurrentFragment);
                displayCurrentFragment(mCurrentFragment,String.valueOf(state.getValue()));
                getActionBar().setTitle(getResources().getString(R.string.title_change_current_store));
                break;
            case Exit:
                exitConfirmation();
                break;
            default:
                break;
        }

        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                lstDrawer.setItemChecked(state.getValue(), true);
                lstDrawer.setSelection(state.getValue());
            }
        });
    }

    private void putTokenToFragment(Fragment fragment) {
        Bundle bundle = new Bundle();
        bundle.putString(Constants.ACCESS_TOKEN, mToken);
        fragment.setArguments(bundle);
    }

    private void displayCurrentFragment(Fragment fragment, String tag){
        final android.app.FragmentManager fragmentManager = getFragmentManager();
        fragmentManager.executePendingTransactions();
        fragmentManager.beginTransaction().replace(R.id.content_frame, fragment, tag).commit();
        //todo wait for transaction ends?
    }

    private void exitConfirmation(){
        AlertDialog.Builder ad = new AlertDialog.Builder(this);

        ad.setTitle(getResources().getString(R.string.exitDialogTitle));
        ad.setMessage(getResources().getString(R.string.exitDialogMsg));
        ad.setPositiveButton(getResources().getString(R.string.Yes), new DialogInterface.OnClickListener()
        {
            public void onClick(DialogInterface dialog, int arg1)
            {
                logoff();
            }
        });

        ad.setNegativeButton(getResources().getString(R.string.No), new DialogInterface.OnClickListener()
        {
            public void onClick(DialogInterface dialog, int arg1)
            {}
        });

        ad.setCancelable(false);
        ad.show();
    }

    private void logoff()
    {
        mToken = null;
        Intent objsignOut = new Intent(getBaseContext(),LoginActivity_.class);
        startActivity(objsignOut);
        finish();
    }

    @Override
    public void onFragmentChange(DrawerMenu.AppStates target)
    {
        changeState(target);
    }

    /*@Override
    public SpiceManager getRestClient()
    {
        return mSpiceManager;
    }*/
}
