package ru.dreamkas.pos.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.ArrayList;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.api.Product;

public class ReceiptAdapter extends ProductsAdapter{

    public ArrayList<Product> getItems() {
        return mItems;
    }

    class ReceiptItemHolder{
        TextView txtTitle;
        TextView txtQuantity;
        TextView txtCost;
    }

    public ReceiptAdapter(Context context, int layoutResourceId, ArrayList<Product> data){
        super(context, layoutResourceId, data);
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        return getView(position, convertView, parent, layoutResourceId);
    }

    @Override
    protected View getView(int position, View convertView, ViewGroup parent, int layoutResourceId){
        View row = convertView;
        ReceiptItemHolder holder;

        if(row == null)
        {
            LayoutInflater inflater = LayoutInflater.from(context);
            row = inflater.inflate(layoutResourceId, parent, false);

            holder = new ReceiptItemHolder();
            holder.txtTitle = (TextView)row.findViewById(R.id.txtReceiptItemTitle);
            holder.txtQuantity = (TextView)row.findViewById(R.id.txtReceiptItemQuantity);
            holder.txtCost = (TextView)row.findViewById(R.id.txtReceiptItemCost);
            row.setTag(holder);
        }
        else
        {
            holder = (ReceiptItemHolder)row.getTag();
        }

        Product namedObject = mItems.get(position);
        holder.txtTitle.setText(String.format("%s / %s" + (namedObject.getBarcode() == null ? "" : " / " + namedObject.getBarcode()), namedObject.getName(), namedObject.getSku()));
        holder.txtQuantity.setText("1.0 кг");
        holder.txtCost.setText("0.00 Р");

        return row;
    }
}
