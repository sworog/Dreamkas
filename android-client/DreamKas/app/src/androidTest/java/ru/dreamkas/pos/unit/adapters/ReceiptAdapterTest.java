package ru.dreamkas.pos.unit.adapters;

import android.test.AndroidTestCase;
import android.text.SpannableStringBuilder;
import android.view.View;
import android.widget.TextView;

import java.util.ArrayList;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.adapters.ReceiptAdapter;
import ru.dreamkas.pos.model.ReceiptItem;
import ru.dreamkas.pos.model.api.Product;
import ru.dreamkas.pos.view.misc.StringDecorator;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class ReceiptAdapterTest extends AndroidTestCase {
    private ReceiptAdapter mAdapter;
    private Product product1;

    public ReceiptAdapterTest() {
        super();
    }

    protected void setUp() throws Exception {
        super.setUp();
        ArrayList<ReceiptItem> data = new ArrayList<ReceiptItem>();

        product1 = new Product();
        product1.setName("test1");
        product1.setSku("10002");

        Product product2 = new Product();
        product2.setName("test2");
        product2.setSku("10003");

        data.add(new ReceiptItem(product1));
        data.add(new ReceiptItem(product2));

        mAdapter = new ReceiptAdapter(getContext(), R.layout.receipt_listview_item, data);
    }

    public void testItemViewContentTitle(){
        View view = mAdapter.getView(0, null, null);
        String origin = String.format("%s / %s", product1.getName(), product1.getSku());
        TextView title = (TextView) view.findViewById(R.id.txtReceiptItemTitle);

        assertThat("Title view string doesn't match.", title.getText().toString(), is(origin));
    }

    public void testItemViewContentQuantity(){
        View view = mAdapter.getView(0, null, null);

        TextView quantity = (TextView) view.findViewById(R.id.txtReceiptItemQuantity);
        String origin = String.format("1,0 %s", product1.getUnits() == null ? "шт" : product1.getUnits());

        assertThat("Quantity view string doesn't match.", quantity.getText().toString(), is(origin));
    }

    public void testItemViewContentSellingPrice(){
        View view = mAdapter.getView(0, null, null);

        TextView cost = (TextView) view.findViewById(R.id.txtReceiptItemCost);
        String origin = String.format("%s %c", product1.getSellingPrice() == null ? "0,00" : product1.getSellingPrice(), StringDecorator.RUBLE_CODE);

        assertThat("Selling price view string doesn't match", cost.getText().toString(), is(origin));
    }
}
