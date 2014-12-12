package ru.dreamkas.pos.adapters;

import android.content.Context;
import android.text.SpannableStringBuilder;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.view.animation.TranslateAnimation;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;

import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;
import java.text.NumberFormat;
import java.util.ArrayList;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.ReceiptItem;
import ru.dreamkas.pos.view.misc.StringDecorator;

public class ReceiptAdapter extends ArrayAdapter<ReceiptItem> {
    Context context;
    int layoutResourceId;
    ArrayList<ReceiptItem> mItems = null;
    private int lastPosition = -1;

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

            /*if(parent.getChildCount() > 0){
                parent.getChildAt(parent.getChildCount()-1).bringToFront();
            }*/
        }
        else
        {
            holder = (ReceiptItemHolder)row.getTag();

           /* Animation animation = new TranslateAnimation(0, 0, (position > lastPosition) ? 100 : -100, 0);
            animation.setDuration(400);
            row.startAnimation(animation);*/
        }

        if(lastPosition + 1 == position){

            if(parent.getChildCount() > 0){
                parent.getChildAt(parent.getChildCount()-1).bringToFront();
            }

            Animation animation = new TranslateAnimation(0, 0, -50, 0);
            animation.setDuration(400);
            row.startAnimation(animation);

            lastPosition = position;
        }

        DecimalFormatSymbols otherSymbols = new DecimalFormatSymbols();
        otherSymbols.setDecimalSeparator(',');

        DecimalFormat quantityFormat = new DecimalFormat("0", otherSymbols);
        quantityFormat.setParseBigDecimal(true);
        quantityFormat.setMinimumFractionDigits(1);
        quantityFormat.setMaximumFractionDigits(Constants.SCALE_QUANTITY);

        ReceiptItem namedObject = mItems.get(position);
        holder.txtTitle.setText(namedObject.getProduct().getName());
        holder.txtQuantity.setText(String.format("x%s %s", quantityFormat.format(namedObject.getQuantity()), namedObject.getProduct().getUnits() == null ? "шт" : namedObject.getProduct().getUnits()));

        SpannableStringBuilder cost = StringDecorator.buildStringWithRubleSymbol(DreamkasApp.getResourceString(R.string.msg_info_ruble_value), DreamkasApp.getMoneyFormat().format(namedObject.getTotal()), StringDecorator.RUBLE_CODE);
        holder.txtCost.setText(cost);





        return row;
    }
}
