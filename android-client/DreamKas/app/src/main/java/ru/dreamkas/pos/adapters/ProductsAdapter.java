package ru.dreamkas.pos.adapters;

import android.app.Activity;
import android.content.Context;
import android.graphics.Color;
import android.text.Html;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.SpannableStringBuilder;
import android.text.style.BackgroundColorSpan;
import android.text.style.ForegroundColorSpan;
import android.text.style.StyleSpan;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import java.text.Normalizer;
import java.util.ArrayList;
import ru.dreamkas.pos.DreamkasApp;
import ru.dreamkas.pos.R;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.view.components.regular.TextViewTypefaced;
import ru.dreamkas.pos.view.misc.StringDecorator;

public class ProductsAdapter extends ArrayAdapter<Product>{
    Context context;
    int layoutResourceId;
    ArrayList<Product> mItems = null;
    String mHighlightStr = null;

    public ArrayList<Product> getItems() {
        return mItems;
    }

    class NamedObjectHolder{
        TextViewTypefaced txtTitle;
        TextViewTypefaced txtDescription;
        TextViewTypefaced txtSellingPrice;
    }

    public ProductsAdapter(Context context, int layoutResourceId, ArrayList<Product> data){
        super(context, layoutResourceId, data);
        this.layoutResourceId = layoutResourceId;
        this.context = context;
        this.mItems = data;
        this.mHighlightStr = "";
    }

    public ProductsAdapter(Context context, int layoutResourceId, ArrayList<Product> data, String highlightStr){
        super(context, layoutResourceId, data);
        this.layoutResourceId = layoutResourceId;
        this.context = context;
        this.mItems = data;
        this.mHighlightStr = highlightStr;
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
            holder.txtTitle = (TextViewTypefaced)row.findViewById(R.id.lblTitle);
            holder.txtDescription = (TextViewTypefaced)row.findViewById(R.id.lblDescription);
            holder.txtSellingPrice = (TextViewTypefaced)row.findViewById(R.id.lblSellingPrice);

            row.setTag(holder);
        }
        else
        {
            holder = (NamedObjectHolder)row.getTag();
        }

        Product namedObject = mItems.get(position);

        CharSequence title = highlight(namedObject.getName());
        CharSequence description = highlight(String.format("%s " + (namedObject.getBarcode() == null ? "" : " / " + namedObject.getBarcode()), namedObject.getSku()));

        holder.txtTitle.setText(title);
        //holder.txtSellingPrice.setSpacing(0.5f);
        holder.txtDescription.setText(description);



        if(namedObject.getSellingPrice() != null){
            SpannableStringBuilder cost = StringDecorator.buildStringWithRubleSymbol(true, DreamkasApp.getResourceString(R.string.msg_info_ruble_value), DreamkasApp.getMoneyFormat().format(namedObject.getSellingPrice()), StringDecorator.RUBLE_CODE);
            holder.txtSellingPrice.setText(cost);
        }else {
            holder.txtSellingPrice.setText("");
        }

        //holder.txtSellingPrice.setSpacing(1);


        return row;
    }

    private CharSequence highlight(String originalText) {
        String normalizedText = Normalizer.normalize(originalText, Normalizer.Form.NFD).replaceAll("\\p{InCombiningDiacriticalMarks}+", "").toLowerCase();

        int start = normalizedText.indexOf(mHighlightStr);
        if (start < 0) {
            return originalText;
        } else {
            Spannable highlighted = new SpannableString(originalText);
            while (start >= 0) {
                int spanStart = Math.min(start, originalText.length());
                int spanEnd = Math.min(start + mHighlightStr.length(), originalText.length());

                highlighted.setSpan(new ForegroundColorSpan(Color.argb((int)Math.round(255*0.87), 0,0,0)), spanStart, spanEnd, Spannable.SPAN_EXCLUSIVE_EXCLUSIVE);

                start = normalizedText.indexOf(mHighlightStr, spanEnd);
            }
            return highlighted;
        }
    }
}
