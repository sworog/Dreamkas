package ru.dreamkas.pos.adapters;

import android.content.Context;
import android.text.SpannableStringBuilder;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;
import java.util.ArrayList;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.ReceiptItem;
import ru.dreamkas.pos.view.misc.StringDecorator;

public class ReceiptAdapter extends ArrayAdapter<ReceiptItem> {
    Context context;
    int layoutResourceId;
    ArrayList<ReceiptItem> mItems = null;

    public ArrayList<ReceiptItem> getItems() {
        return mItems;
    }

    class ReceiptItemHolder{
        TextView txtTitle;
        TextView txtQuantity;
        TextView txtCost;
    }

    public ReceiptAdapter(Context context, int layoutResourceId, ArrayList<ReceiptItem> data){
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

        ReceiptItem namedObject = mItems.get(position);
        holder.txtTitle.setText(String.format("%s / %s" + (namedObject.getProduct().getBarcode() == null ? "" : " / " + namedObject.getProduct().getBarcode()), namedObject.getProduct().getName(), namedObject.getProduct().getSku()));
        holder.txtQuantity.setText(String.format("%s %s", namedObject.getQuantity().toString(), namedObject.getProduct().getUnits() == null ? "шт" : namedObject.getProduct().getUnits()));

        SpannableStringBuilder cost = StringDecorator.buildStringWithRubleSymbol("%s %c",  namedObject.getSellingPrice() == null ? "0.00" : namedObject.getTotal().toString() ,StringDecorator.RUBLE_CODE);
        holder.txtCost.setText(cost);

        return row;
    }
}
