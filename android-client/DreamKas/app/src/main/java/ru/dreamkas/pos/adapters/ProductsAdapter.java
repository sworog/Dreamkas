package ru.dreamkas.pos.adapters;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.ArrayList;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.api.Product;

public class ProductsAdapter extends ArrayAdapter<Product>{
    Context context;
    int layoutResourceId;
    ArrayList<Product> mItems = null;

    public ArrayList<Product> getItems() {
        return mItems;
    }

    class NamedObjectHolder{
        TextView txtTitle;
    }

    public ProductsAdapter(Context context, int layoutResourceId, ArrayList<Product> data){
        super(context, layoutResourceId, data);
        this.layoutResourceId = layoutResourceId;
        this.context = context;
        this.mItems = data;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        return getView(position, convertView, parent, layoutResourceId);
    }

    protected View getView(int position, View convertView, ViewGroup parent, int layoutResourceId){
        View row = convertView;
        NamedObjectHolder holder;

        if(row == null)
        {
            LayoutInflater inflater = LayoutInflater.from(context);
            row = inflater.inflate(layoutResourceId, parent, false);

            holder = new NamedObjectHolder();
            holder.txtTitle = (TextView)row.findViewById(R.id.txtListItemTitle);
            row.setTag(holder);
        }
        else
        {
            holder = (NamedObjectHolder)row.getTag();
        }

        Product namedObject = mItems.get(position);
        holder.txtTitle.setText(String.format("%s / %s" + (namedObject.getBarcode() == null ? "" : " / " + namedObject.getBarcode()), namedObject.getName(), namedObject.getSku()));

        return row;
    }
}
