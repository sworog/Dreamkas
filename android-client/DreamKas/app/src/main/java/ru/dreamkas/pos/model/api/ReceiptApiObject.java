package ru.dreamkas.pos.model.api;

import android.support.annotation.Nullable;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonInclude;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.TimeZone;

import ru.dreamkas.pos.model.Receipt;
import ru.dreamkas.pos.model.ReceiptItem;

@JsonInclude(JsonInclude.Include.NON_EMPTY)
@JsonIgnoreProperties(ignoreUnknown=true)
public class ReceiptApiObject {
    private ArrayList<Product> products;
    private PaymentApiObject payment;
    private String date;

    public ReceiptApiObject(Receipt receipt) {
        products = new ArrayList<Product>();

        for(ReceiptItem product : receipt){
            products.add(new Product(product.getProduct().getId(), product.getQuantity().doubleValue(), product.getSellingPrice().doubleValue()));
        }

        switch (receipt.getPaymentMethod()){
            case CASH:
                payment = new PaymentApiObject(receipt.getAmountTendered().doubleValue());
                break;
            case BANCCARD:
                payment = new PaymentApiObject();
                break;
        }

        TimeZone tz = TimeZone.getDefault();
        DateFormat df = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm'Z'");
        df.setTimeZone(tz);
        date = df.format(new Date());
    }

    public ArrayList<Product> getProducts() {
        return products;
    }

    public PaymentApiObject getPayment() {
        return payment;
    }

    public String getDate() {
        return date;
    }

    @JsonInclude(JsonInclude.Include.NON_EMPTY)
    @JsonIgnoreProperties(ignoreUnknown=true)
    public class Product {
        private String product;
        private Double price;
        private Double quantity;

        public Product(String id, double quantity, double price) {
           this.product = id;
           this.quantity = quantity;
           this.price = price;
        }

        public String getProduct() {
            return product;
        }
        public Double getPrice() {
            return price;
        }
        public Double getQuantity() {
            return quantity;
        }
    }


}