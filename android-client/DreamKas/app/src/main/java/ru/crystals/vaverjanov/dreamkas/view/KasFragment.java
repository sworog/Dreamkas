package ru.crystals.vaverjanov.dreamkas.view;

import android.text.Editable;
import android.text.TextWatcher;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TextView;

import org.androidannotations.annotations.AfterViews;
import org.androidannotations.annotations.EFragment;
import org.androidannotations.annotations.ViewById;
import org.springframework.util.StringUtils;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.model.DreamkasFragments;
import ru.crystals.vaverjanov.dreamkas.model.NamedObject;

@EFragment(R.layout.fragment_kas)
public class KasFragment extends BaseFragment
{
    private PreferencesManager preferences;

    @ViewById
    TextView lblStore;

    @ViewById
    ListView list_view;

    @ViewById
    EditText inputSearch;

   @AfterViews
   void init()
   {
       String products[] = {"111", "222", "H22211", "44444", "33322E"};

       ArrayAdapter<String> adapter = new ArrayAdapter<String>(getActivity(), R.layout.list_view_row_item, R.id.textViewItem, products);
       list_view.setAdapter(adapter);

       inputSearch.addTextChangedListener(new TextWatcher() {

           @Override
           public void onTextChanged(CharSequence cs, int arg1, int arg2, int arg3)
           {
               //set new adapter to lv
           }

           @Override
           public void beforeTextChanged(CharSequence arg0, int arg1, int arg2,
                                         int arg3) {
               // TODO Auto-generated method stub

           }

           @Override
           public void afterTextChanged(Editable arg0) {
               // TODO Auto-generated method stub
           }
       });


   }

    @Override
    public void onStart()
    {
        super.onStart();

        preferences = PreferencesManager.getInstance();

        if(StringUtils.hasText(preferences.getCurrentStore()))
        {

            lblStore.setText(preferences.getCurrentStore());
        }
        else
        {
            changeFragmentCallback.onFragmentChange(DreamkasFragments.Store);
        }
    }
}
