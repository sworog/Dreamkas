package ru.dreamkas.pos.adapters;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.List;

import ru.dreamkas.pos.model.api.NamedObject;

public class NamedObjectsAdapter extends ArrayAdapter<NamedObject>{
    Context context;
    int layoutResourceId;
    ArrayList<NamedObject> data = null;

    class NamedObjectHolder{
        TextView txtTitle;
    }

    public NamedObjectsAdapter(Context context, int layoutResourceId, ArrayList<NamedObject> data){
        super(context, layoutResourceId, data);
        this.layoutResourceId = layoutResourceId;
        this.context = context;
        this.data = data;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        return getView(position, convertView, parent, layoutResourceId);
    }

    protected View getView(int position, View convertView, ViewGroup parent, int layoutResourceId){
        View row = convertView;
        NamedObjectHolder holder;

        if(row == null){
            LayoutInflater inflater = ((Activity)context).getLayoutInflater();
            row = inflater.inflate(layoutResourceId, parent, false);

            holder = new NamedObjectHolder();
            holder.txtTitle = (TextView)row.findViewById(android.R.id.text1);
            row.setTag(holder);
        }
        else{
            holder = (NamedObjectHolder)row.getTag();
        }

        NamedObject namedObject = data.get(position);
        holder.txtTitle.setText(namedObject.getName());

        return row;
    }

    public List<? extends NamedObject> getItems() throws Exception {
        throw new Exception("You should override getItems in subClass");
    }
}
