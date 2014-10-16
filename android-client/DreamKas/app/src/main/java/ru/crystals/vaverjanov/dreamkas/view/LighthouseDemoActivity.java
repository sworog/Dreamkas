package ru.crystals.vaverjanov.dreamkas.view;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Fragment;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.ActionBarDrawerToggle;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.view.MenuItem;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import com.octo.android.robospice.SpiceManager;
import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.EActivity;
import org.androidannotations.annotations.ItemClick;
import org.androidannotations.annotations.ViewById;
import org.springframework.util.StringUtils;
import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.IChangeFragmentContainer;
import ru.crystals.vaverjanov.dreamkas.controller.LighthouseSpiceService;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.model.DrawerMenu;


@EActivity(R.layout.activity_lighhouse_demo)
public class LighthouseDemoActivity extends Activity implements IChangeFragmentContainer
{
    @ViewById
    DrawerLayout drawer_layout;

    @ViewById
    ListView lstDrawer;

    ActionBarDrawerToggle drawerToggle;

    private SpiceManager spiceManager = new SpiceManager(LighthouseSpiceService.class);

    private String token;
    private Fragment mCurrentFragment;

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_lighhouse_demo);

        Intent intent = getIntent();
        token = intent.getStringExtra("access_token");
    }

    @AfterViews
    void initDrawer()
    {
        drawer_layout.setDrawerShadow(R.drawable.drawer_shadow, GravityCompat.START);

        lstDrawer.setAdapter(new ArrayAdapter<String>(this, R.layout.drawer_list_item, DrawerMenu.getMenuItems().values().toArray(new String[DrawerMenu.getMenuItems().size()])));
        lstDrawer.setChoiceMode(ListView.CHOICE_MODE_SINGLE);
        lstDrawer.setSelection(0);

        getActionBar().setDisplayHomeAsUpEnabled(true);
        getActionBar().setHomeButtonEnabled(true);
        getActionBar().setTitle(getResources().getString(R.string.dream_kas_title));
        drawerToggle = new ActionBarDrawerToggle(this, drawer_layout, R.drawable.ic_drawer, R.string.drawer_open, R.string.drawer_close)
        {
            public void onDrawerClosed(View view)
            {
                getActionBar().setTitle(getResources().getString(R.string.dream_kas_title));
                invalidateOptionsMenu();
            }

            public void onDrawerOpened(View drawerView)
            {
                getActionBar().setTitle(getResources().getString(R.string.drawer_title));
                invalidateOptionsMenu();
            }
        };
        drawer_layout.setDrawerListener(drawerToggle);

        drawerToggle.syncState();

        if(!StringUtils.hasText(PreferencesManager.getInstance().getCurrentStore()))
        {
            changeState(DrawerMenu.AppStates.Store);
        }else
        {
            changeState(DrawerMenu.AppStates.Kas);
        }
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item)
    {
        if (drawerToggle.onOptionsItemSelected(item))
        {
            return true;
        }

        switch(item.getItemId())
        {
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    @ItemClick
    void lstDrawerItemClicked(int position)
    {
        lstDrawer.setSelection(position);
        changeState(DrawerMenu.AppStates.fromValue(position));
        drawer_layout.closeDrawer(lstDrawer);
    }

    @Override
    public void onStart()
    {
        spiceManager.start(this);
        super.onStart();
    }

    @Override
    public void onStop()
    {
        spiceManager.shouldStop();
        super.onStop();
    }



    public void changeState(final DrawerMenu.AppStates state)
    {
        //Fragment currentFragment = null;
        switch (state)
        {
            case Kas:
                mCurrentFragment = new KasFragment_();
                displayCurrentFragment(mCurrentFragment, String.valueOf(state.getValue()));
                break;
            case Store:
                mCurrentFragment = new StoreFragment_();
                displayCurrentFragment(mCurrentFragment,String.valueOf(state.getValue()));
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

    private void displayCurrentFragment(Fragment fragment, String tag)
    {
        final android.app.FragmentManager fragmentManager = getFragmentManager();
        fragmentManager.executePendingTransactions();
        fragmentManager.beginTransaction().replace(R.id.content_frame, fragment, tag).commit();
        //todo wait for transaction ends?
    }

    private void exitConfirmation()
    {
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
        token = null;
        Intent objsignOut = new Intent(getBaseContext(),LoginActivity_.class);
        startActivity(objsignOut);
        finish();
    }

    public String getToken() {
        return token;
    }

    @Override
    public void onFragmentChange(DrawerMenu.AppStates target)
    {
        changeState(target);
    }

    @Override
    public SpiceManager getRestClient()
    {
        return spiceManager;
    }

    public Fragment getCurrentFragment() {
        return mCurrentFragment;
    }
}
