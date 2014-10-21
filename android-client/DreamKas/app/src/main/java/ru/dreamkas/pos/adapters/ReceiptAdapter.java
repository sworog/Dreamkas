package ru.dreamkas.pos.adapters;

import android.content.Context;
import android.text.SpannableStringBuilder;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.ArrayList;

import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.view.misc.StringDecorator;

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
        holder.txtQuantity.setText(String.format("1.0 %s", namedObject.getUnits() == null ? "шт" : namedObject.getUnits()));

        SpannableStringBuilder cost = StringDecorator.buildStringWithRubleSymbol("%d %c",  namedObject.getSellingPrice() == null ? 0 : namedObject.getSellingPrice() ,StringDecorator.RUBLE_CODE);
        holder.txtCost.setText(cost);

        return row;
    }
}
