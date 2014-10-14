package ru.crystals.vaverjanov.dreamkas.view;

import android.os.Bundle;
import android.widget.TextView;

import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;

@EFragment(R.layout.fragment_kas)
public class KasFragment extends BaseFragment
{
    private PreferencesManager preferences;

    @ViewById
    TextView lblStore;

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
    }
}
