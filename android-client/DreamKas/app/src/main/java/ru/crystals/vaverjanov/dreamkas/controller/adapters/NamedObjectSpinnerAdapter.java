package ru.crystals.vaverjanov.dreamkas.controller.adapters;

import android.content.Context;
import android.view.View;
import android.view.ViewGroup;
import android.widget.SpinnerAdapter;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.List;

import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;

public class NamedObjectSpinnerAdapter extends NamedObjectsAdapter implements SpinnerAdapter
{
    private int dropdownLayoutResourceId;


    public NamedObjectSpinnerAdapter(Context context, int layoutResourceId, int dropdownLayoutResourceId, ArrayList<NamedObject> data)
    {
        super(context, layoutResourceId, data);

        this.dropdownLayoutResourceId = dropdownLayoutResourceId;

        this.add(new NamedObject(null, "<Магазин не выбран>"));
    }

    @Override
    public View getDropDownView(int i, View view, ViewGroup viewGroup)
    {
        return getView(i, view, viewGroup, dropdownLayoutResourceId);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        View v = super.getView(position, convertView, parent);
        if (position == getCount()) {
            ((TextView)v.findViewById(android.R.id.text1)).setText("");
            ((TextView)v.findViewById(android.R.id.text1)).setHint(getItem(getCount()).getName()); //"Hint to be displayed"
        }

        return v;
    }

    @Override
    public List<NamedObject> getItems() {

        return this.data.subList(1, this.getCount());
    }

    @Override
    public int getCount() {
        return super.getCount()-1;
    }

    public int getHintElementIndex() {
        return this.getCount();
    }
}
