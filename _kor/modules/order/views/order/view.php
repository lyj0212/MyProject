<div class="table_wrap">
    <table class="bbs_table viewType">
        <caption>"<?php echo $data['title']; ?>" 상세</caption>
        <thead>
        <tr>
            <td scope="row" class="subject"><strong class="bbsTitle"><?php echo $data['title']; ?></strong></td>
        </tr>
        <tr>
            <td>
                <div class="tbl_cnts_info">
                    <dl>
                        <dt>작성자</dt>
                        <dd><?php echo $data['name']; ?></dd>
                    </dl>
                    <dl>
                        <dt>작성일</dt>
                        <dd><?php echo date('Y.m.d H:i', strtotime($data['created'])); ?></dd>
                        <dt>조회수</dt>
                        <dd><?php echo $data['hit']; ?></dd>
                    </dl>
                </div>
            </td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="cnts">
                <?php echo $data['contents']; ?>
            </td>
        </tr>
        <?php if( ! empty($files[$data['id']])) : ?>
            <tr>
                <td>
                    <div class="attachment_wrap">
                        <ul class="attachment">
                            <?php foreach($files[$data['id']] as $item) : ?>
                                <li>
                                    <a href="<?php echo $item['download_link']; ?>" class="_tooltip" data-pjax="false" title="다운로드 : <?php echo number_format($item['download_count']); ?> 회"><?php echo $item['ext_icon']; ?> <?php echo $item['orig_name']; ?><span class="file_size">(<?php echo human_filesize($item['file_size']); ?>)</span></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <div class="table_footer">

        <?php
        $params = array(
            'pid' => $data['id']
        );
        //echo CI::$APP->_comment($params);
        ?>

        <p class="btn_wrap btn_sm text-right">
            <?php if($this->auth->check(array('action'=>'write')) == TRUE) : ?>
                <a href="<?php echo $this->link->get(array('action'=>'write')); ?>" class="btn btn-sm btn-modify"><span class="icon-edit">수정</span></a>
            <?php endif; ?>
            <?php if($this->auth->check(array('action'=>'delete')) == TRUE) : ?>
                <a href="<?php echo $this->link->get(array('action'=>'delete')); ?>" onclick="return confirm('삭제 하시겠습니까?');" class="btn btn-sm btn-delete"><span class="icon-cancel">삭제</span></a>
            <?php endif; ?>
            <a href="<?php echo $this->link->get(array('action'=>'index', 'id'=>NULL)); ?>" class="btn btn-sm btn-list"><span class="icon-menu">목록</span></a>
        </p>
    </div>
</div>
