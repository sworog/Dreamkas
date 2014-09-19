package ru.crystals.vaverjanov.dreamkas.controller.adapters;

import android.app.Activity;
import android.content.Context;
import android.database.DataSetObserver;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.SpinnerAdapter;
import android.widget.TextView;

import java.util.ArrayList;

import ru.crystals.vaverjanov.dreamkas.model.NamedObject;

public class NamedObjectSpinnerAdapter extends NamedObjectsAdapter implements SpinnerAdapter
{
    private int dropdownLayoutResourceId;

    public NamedObjectSpinnerAdapter(Context context, int layoutResourceId, int dropdownLayoutResourceId, ArrayList<NamedObject> data)
    {
        super(context, layoutResourceId, data);
        this.dropdownLayoutResourceId = dropdownLayoutResourceId;
    }

    @Override
    public View getDropDownView(int i, View view, ViewGroup viewGroup)
    {
        return getView(i, view, viewGroup, dropdownLayoutResourceId);
    }

   /* @Override
    public void registerDataSetObserver(DataSetObserver dataSetObserver) {

    }

    @Override
    public void unregisterDataSetObserver(DataSetObserver dataSetObserver) {

    }*/

    /*@Override
    public int getCount()
    {
        return data.size();
    }*/

    /*@Override
    public NamedObject getItem(int i)
    {
        return data.get(i);
    }*/

   /* @Override
    public long getItemId(int i) {
        return 0;
    }*/

    /*@Override
    public boolean hasStableIds() {
        return false;
    }*/

    /*@Override
    public View getView(int i, View convertView, ViewGroup viewGroup) {
        View row = convertView;
        NamedObjectHolder holder = null;

        if(row == null)
        {
            LayoutInflater inflater = ((Activity)context).getLayoutInflater();
            row = inflater.inflate(layoutResourceId, parent, false);

            holder = new NamedObjectHolder();
            holder.txtTitle = (TextView)row.findViewById(android.R.id.text1);
            row.setTag(holder);
        }
        else
        {
            holder = (NamedObjectHolder)row.getTag();
        }

        NamedObject namedObject = data.get(position);
        holder.txtTitle.setText(namedObject.getName());

        return row;
    }*/

   /* @Override
    public int getItemViewType(int i) {
        return 0;
    }*/

    /*@Override
    public int getViewTypeCount() {
        return 0;
    }*/

    /*@Override
    public boolean isEmpty() {
        return false;
    }*/
}
