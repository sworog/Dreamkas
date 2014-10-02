package ru.crystals.vaverjanov.dreamkas.controller.adapters;

import android.content.Context;
import android.view.View;
import android.view.ViewGroup;
import android.widget.SpinnerAdapter;

import java.util.ArrayList;
import java.util.List;

import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;

public class NamedObjectSpinnerAdapter extends NamedObjectsAdapter implements SpinnerAdapter
{
    private int dropdownLayoutResourceId;
    private final ArrayList<NamedObject> items;

    public NamedObjectSpinnerAdapter(Context context, int layoutResourceId, int dropdownLayoutResourceId, ArrayList<NamedObject> data)
    {
        super(context, layoutResourceId, data);

        this.dropdownLayoutResourceId = dropdownLayoutResourceId;

        data.add(0, new NamedObject(null, "<Магазин не выбран>"));
        items = data;

        data = null;
    }

    @Override
    public View getDropDownView(int i, View view, ViewGroup viewGroup)
    {
        return getView(i, view, viewGroup, dropdownLayoutResourceId);
    }

    public List<NamedObject> getItems() {

        return items.subList(1, this.getCount());
    }
}
